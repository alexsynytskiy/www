<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use amnah\yii2\user\models\User;
use dosamigos\transliterator\TransliteratorHelper;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property integer $is_public
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_top
 * @property integer $is_video
 * @property integer $content_category_id
 * @property integer $comments_count
 * @property integer $is_cover
 * @property integer $is_index
 * @property string $source_title
 * @property string $source_url
 * @property integer $photo_id
 * @property integer $is_yandex_rss
 * @property string $cached_tag_list
 * @property integer $allow_comment
 *
 * @property Users $user
 */
class Post extends ActiveRecord
{

    /**
     * @var int content_category_id for news
     */
    const CATEGORY_NEWS = 1;
    /**
     * @var int content_category_id for blog
     */
    const CATEGORY_BLOG = 2;

    /**
     * @var array Images[]
     */
    public $image;

    /**
     * @var string Coordinates data for crop image
     */
    public $cropData;

    /**
     * @var string Tags
     */
    public $tags;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'is_public', 'is_top', 'is_video', 'content_category_id', 'comments_count', 'is_cover', 'is_index', 'photo_id', 'is_yandex_rss', 'allow_comment'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at', 'tags'], 'safe'],
            [['title', 'slug', 'source_title', 'source_url', 'cached_tag_list'], 'string', 'max' => 255],

            //required
            [['title', 'content', 'content_category_id'], 'required'],

            // image
            [['image'], 'file', 'extensions' => 'jpeg, gif, png', 'on' => ['create', 'update']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID записи',
            'user_id' => 'Автор',
            'title' => 'Заголовок',
            'slug' => 'URL псевдоним',
            'content' => 'Контент',
            'is_public' => 'Опубликовано',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'is_top' => 'Закреплено',
            'is_video' => 'С видео',
            'content_category_id' => 'Категория',
            'comments_count' => 'Количество комментариев',
            'is_cover' => 'Обложка',
            'is_index' => 'Главная',
            'source_title' => 'Название источника',
            'source_url' => 'Адрес источника',
            'photo_id' => 'Фото',
            'is_yandex_rss' => 'Яндекс RSS',
            'cached_tag_list' => 'Закешированный список тегов',
            'allow_comment' => 'Можно комментировать',
            'image' => 'Изображение',
            'tags' => 'Теги',
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Get author's username
     *
     * @return string
     */
    public function getUserName(){
        return $this->user->username;
    }


    /**
     * Get list of categories for creating dropdowns
     *
     * @return array Array of string
     */
    public static function categoryDropdown()
    {
        static $dropdown;
        if ($dropdown === null) {

            $dropdown[self::CATEGORY_BLOG] = self::categoryHumanName(self::CATEGORY_BLOG);
            $dropdown[self::CATEGORY_NEWS] = self::categoryHumanName(self::CATEGORY_NEWS);

        }
        return $dropdown;
    }

    /**
     * Get category human name
     *
     * @param int $category_id
     * @return string
     */
    public static function categoryHumanName($category_id)
    {
        if ($category_id == self::CATEGORY_BLOG) return 'Блог';
        elseif ($category_id == self::CATEGORY_NEWS) return 'Новости';

        return 'Не определено';
    }

    /**
     * Get current category
     *
     * @return string
     */
    public function getCategory()
    {
        return self::categoryHumanName($this->content_category_id);
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
        return Asset::getAssets($this->id, Asset::ASSETABLE_POST, $thumbnail, true);
    }

    /**
     * @return array Array of Tag
     */
    public function getTags()
    {
        $tagging = Tagging::find()
            ->where([
                'taggable_id' => $this->id,
                'taggable_type' => Tagging::TAGGABLE_POST,
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
                'taggable_type' => Tagging::TAGGABLE_POST,
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
            $tagging->taggable_type = Tagging::TAGGABLE_POST;
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
        $slug = TransliteratorHelper::process($slug, '-', 'en');
        $slug = str_replace(["ʹ",'?','.',',','@','!','#','$','%','^','&','*','(',')','{','}','[',']','+',':',';','"',"'",'`','~','\\','/','|','№'], "", $slug);
        $slug = str_replace(" ", "-", $slug);
        $slug = strtolower($slug);
        return $slug;
    }

}
