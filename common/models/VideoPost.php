<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\user\models\User;
use dosamigos\transliterator\TransliteratorHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "video".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property integer $is_public
 * @property string $created_at
 * @property string $updated_at
 * @property string $cached_tag_list
 * @property integer $is_pin
 */
class VideoPost extends ActiveRecord
{
    /**
     * @var Asset
     */
    public $image;

    /**
     * @var Asset
     */
    public $video;

    /**
     * @var string Tags
     */
    public $tags;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'title'], 'required'],
            [['id', 'user_id', 'is_public', 'is_pin'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at', 'tags'], 'safe'],
            [['title', 'slug', 'cached_tag_list'], 'string', 'max' => 255],

            // image
            [['image'], 'file', 'extensions' => 'jpeg, jpg, gif, png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'title' => 'Заголовок',
            'slug' => 'URL псевдоним',
            'content' => 'Содержимое',
            'is_public' => 'Опубликовано',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'cached_tag_list' => 'Закешированный список тегов',
            'is_pin' => 'Закреплено',
            'tags' => 'Теги',
            'image' => 'Изображение',
            'video' => 'Видеофайл',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'      => 'yii\behaviors\TimestampBehavior',
                'value'      => function () { return date("Y-m-d H:i:s"); },
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        Tagging::deleteAll(['taggable_type' => Tagging::TAGGABLE_VIDEO ,'taggable_id' => $this->id]);
        Relation::deleteAll(['relationable_type' => Relation::RELATIONABLE_VIDEO ,'relationable_id' => $this->id]);
        Comment::deleteAll(['commentable_type' => Comment::COMMENTABLE_VIDEO ,'commentable_id' => $this->id]);
        CommentCount::deleteAll(['commentable_type' => CommentCount::COMMENTABLE_VIDEO ,'commentable_id' => $this->id]);
        $assets = Asset::find()
            ->where(['assetable_type' => Asset::ASSETABLE_VIDEO ,'assetable_id' => $this->id])
            ->orWhere(['assetable_type' => Asset::ASSETABLE_VIDEOFILE ,'assetable_id' => $this->id])
            ->all();
        foreach ($assets as $asset) {
            $asset->delete();
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Get short content
     *
     * @return string
     */
    public function getShortContent($min = 200, $max = 350)
    {
        $content = $this->content;
        $content = strip_tags($content);
        if(mb_strlen($content,'UTF-8') <= $min)
        {
            return $content;          
        }
        $cutLength = mb_strpos($content,'. ', $min, 'UTF-8');
        if($cutLength && $cutLength < $max) 
        {
            $content = mb_substr($content, 0, $cutLength, 'UTF-8');
        } 
        else 
        {
            $content = mb_substr($content, 0, $min, 'UTF-8');
            $content = trim($content);
        }
        $content .= '...';
        return $content;
    }

    /**
     * @return string Url to video post
     */
    public function getUrl()
    {
        return Url::to('/video/'.$this->id.'-'.$this->slug);
    }

    /**
     * @return array Array of Asset
     */
    public function getAssets()
    {
        return Asset::getAssets($this->id, Asset::ASSETABLE_POST, NULL);
    }

    /**
     * Get single asset
     *
     * @param string $thumbnail
     *
     * @return Asset
     */
    public function getAsset($thumbnail = NULL)
    {
        return Asset::getAssets($this->id, Asset::ASSETABLE_VIDEO, $thumbnail, true);
    }

    /**
     * Get single video asset
     * @return Asset
     */
    public function getVideoAsset()
    {
        return Asset::getAssets($this->id, Asset::ASSETABLE_VIDEOFILE, NULL, true);
    }

    /**
     * @return array Array of Tag
     */
    public function getTags()
    {
        $tagging = Tagging::find()
            ->where([
                'taggable_id' => $this->id,
                'taggable_type' => Tagging::TAGGABLE_VIDEO,
            ])
            ->asArray()
            ->all();
        $tags = [];
        foreach ($tagging as $data) {
            $tags[] = Tag::findOne($data['tag_id']);
        }
        return $tags;
    }

    /**
     * @param int $id Tag id
     *
     * @return boolean
     */
    public function removeTag($id)
    {
        $tagging = Tagging::find()
            ->where([
                'taggable_id' => $this->id,
                'tag_id' => $id,
                'taggable_type' => Tagging::TAGGABLE_VIDEO,
            ])->one();
        if($tagging) return $tagging->delete();
        return false;
    }

    /**
     * @param mixed $id Tag id OR new tag name
     *
     * @return boolean
     */
    public function addTag($id)
    {
        if(is_numeric($id))
        {
            $tag = Tag::find()->where(['id' => $id])->one();
        }
        elseif(strpos($id,'{new}') !== false)
        {
            $name = mb_substr($id, 5, mb_strlen($id,'UTF-8') - 5,'UTF-8');
            $tag = new Tag();
            $tag->name = $name;
            if(!$tag->save()) return false;
        }

        if(!empty($tag)) {
            $tagging = new Tagging();
            $tagging->taggable_id = $this->id;
            $tagging->taggable_type = Tagging::TAGGABLE_VIDEO;
            $tagging->tag_id = $tag->id;
            return $tagging->save();
        }
        return false;
    }

    /**
     * @param string $title Text to transliteration
     *
     * @return string
     */
    public function genSlug($title)
    {
        $slug = trim($title);
        $slug = str_replace(["я", "Я"], "ya", $slug);
        $slug = str_replace(["ю", "Ю"], "yu", $slug);
        $slug = str_replace(["ш", "Ш"], "sh", $slug);
        $slug = str_replace(["щ", "Щ"], "sch", $slug);
        $slug = str_replace(["ж", "Ж"], "zh", $slug);
        $slug = str_replace(["ч", "Ч"], "ch", $slug);
        $slug = TransliteratorHelper::process($slug, '-', 'en');
        $slug = str_replace(["ʹ",'?','.',',','@','!','#','$','%','^','&','*','(',')','{','}','[',']','+',':',';','"',"'",'`','~','\\','/','|','№'], "", $slug);
        $slug = str_replace(" ", "-", $slug);
        $slug = strtolower($slug);
        return $slug;
    }

    /**
     * Initialize hierachy of comments to post
     * 
     * @return array Array of common\models\Comment
     */
    public function getComments() 
    {
        $comments = Comment::find()
            ->where([
                'commentable_type' => Comment::COMMENTABLE_VIDEO,
                'commentable_id' => $this->id,
            ])
            ->all();

        $sortedComments = [];
        foreach ($comments as $comment) 
        {
            $index = $comment->parent_id == null ? 0 : $comment->parent_id;
            $sortedComments[$index][] = $comment;
        }
        return $sortedComments;
    }

    /**
     * Get amount of photos in album
     * @return int
     */
    public function getCommentsCount() {
        return CommentCount::getCommentCount($this->id, CommentCount::COMMENTABLE_VIDEO);
    }
}
