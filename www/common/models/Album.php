<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use amnah\yii2\user\models\User;
use dosamigos\transliterator\TransliteratorHelper;

/**
 * This is the model class for table "albums".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_public
 * @property string $cached_tag_list
 *
 * @property Users $user
 */
class Album extends ActiveRecord
{
    /**
     * @var array Array of File
     */
    public $images;

    /**
     * @var string Existing assets ids
     */
    public $imagesData;

    /**
     * @var string Tags
     */
    public $tags;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'albums';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['user_id', 'is_public'], 'integer'],
            [['created_at', 'updated_at', 'tags', 'imagesData'], 'safe'],
            [['title', 'slug', 'cached_tag_list'], 'string', 'max' => 255],

            //required
            [['title'], 'required'],

            // images
            [['images'], 'file', 'extensions' => 'jpeg, gif, png', 'on' => ['create', 'update']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'slug' => 'Url псевдоним',
            'description' => 'Описание',
            'user_id' => 'Автор',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'is_public' => 'Опубликовано',
            'cached_tag_list' => 'Закешированный список тегов',
            'images' => 'Изображения',
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
        if(isset($this->user)) {
            return $this->user->username;
        }
        return null;
    }


    /**
     * @return array Array of Asset
     */
    public function getAssets()
    {
        return Asset::getAssets($this->id, Asset::ASSETABLE_ALBUM, NULL);
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
