<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use dosamigos\transliterator\TransliteratorHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "coaches".
 *
 * @property integer $id
 * @property string $name
 * @property string $birthday
 * @property string $slug
 * @property string $position
 * @property string $notes
 * @property string $player_carrer
 * @property string $coach_carrer
 * @property integer $country_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $image
 * @property integer $city_id
 */
class Coach extends ActiveRecord
{
    /**
     * @var image from Asset
     */
    public $photo;

    /**
     * @var string Coordinates data for crop image
     */
    public $cropData;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coaches';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthday', 'created_at', 'updated_at'], 'safe'],
            [['notes', 'player_carrer', 'coach_carrer'], 'string'],
            [['country_id'], 'integer'],
            [['name', 'slug', 'position', 'image'], 'string', 'max' => 255],

            // image
            [['photo'], 'file', 'extensions' => 'jpeg, jpg , gif, png'],
            [['cropData'], 'safe'],

            // required 
            [['name'], 'required'],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'birthday' => 'Дата рождения',
            'slug' => 'Ссылка',
            'position' => 'Должность',
            'notes' => 'Текст',
            'player_carrer' => 'Карьера игрока',
            'coach_carrer' => 'Карьера тренера',
            'country_id' => 'Страна',
            'created_at' => 'Создан',
            'updated_at' => 'Изменён',
            'image' => 'Фото',
            'photo' => 'Фото',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return Asset
     */
    public function getAsset($thumbnail = false)
    {
        return Asset::getAssets($this->id, Asset::ASSETABLE_COACH, $thumbnail, true);
    }

    /**
     * @param string $title Text to transliteration
     *
     * @return string
     */
    public function genSlug()
    {
        $slug = $this->name;
        $slug = trim($slug);
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
     * @return string Url to post
     */
    public function getUrl()
    {
        return Url::to('/coach/'.$this->id.'-'.$this->slug);
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
}
