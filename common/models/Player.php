<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use dosamigos\transliterator\TransliteratorHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "players".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $birthday
 * @property string $slug
 * @property integer $height
 * @property integer $weight
 * @property integer $amplua_id
 * @property integer $country_id
 * @property string $notes
 * @property string $created_at
 * @property string $updated_at
 * @property string $image
 * @property string $more_ampluas
 */
class Player extends ActiveRecord
{
    /**
     * @var File Player photo
     */
    public $avatar;

    /**
     * @var string Coordinates data for crop image
     */
    public $cropData;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'players';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthday', 'created_at', 'updated_at'], 'safe'],
            [['height', 'weight', 'amplua_id', 'country_id'], 'integer'],
            [['notes'], 'string'],
            [['firstname', 'lastname', 'slug', 'image', 'more_ampluas'], 'string', 'max' => 255],

            // required 
            [['lastname', 'amplua_id', 'country_id'], 'required'],

            // image
            [['avatar'], 'file', 'extensions' => 'jpeg, jpg , gif, png'],
            [['cropData'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'birthday' => 'Дата рождения',
            'slug' => 'URL псевдоним',
            'height' => 'Рост',
            'weight' => 'Вес',
            'amplua_id' => 'Амплуа',
            'country_id' => 'Страна',
            'notes' => 'Заметки',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'image' => 'Изображение',
            'avatar' => 'Изображение',
            'more_ampluas' => 'Другие амплуа',
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
     * @return string Player name
     */
    public function getName()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * @return string Url to post
     */
    public function getUrl()
    {
        return Url::to('/player/'.$this->id.'-'.$this->slug);
    }

    /**
     * @return Asset
     */
    public function getAsset($thumbnail = false)
    {
        return Asset::getAssets($this->id, Asset::ASSETABLE_PLAYER, $thumbnail, true);
    }

    /**
     * @param string $title Text to transliteration
     *
     * @return string
     */
    public function genSlug()
    {
        $slug = $this->firstname . ' ' . $this->lastname;
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
     * @return \yii\db\ActiveQuery
     */
    public function getAmplua()
    {
        return $this->hasOne(Amplua::className(), ['id' => 'amplua_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAchievements()
    {
        return $this->hasMany(Achievement::className(), ['player_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranfers()
    {
        return $this->hasMany(Transfer::className(), ['player_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCareers()
    {
        return $this->hasMany(Career::className(), ['player_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberships()
    {
        return $this->hasMany(Membership::className(), ['player_id' => 'id']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getNumber($seasonID = false)
    {
        if(!$seasonID) {
            $seasonID = Season::find()
                ->where(['window' => Season::WINDOW_WINTER])
                ->orderBy(['id' => SORT_DESC])
                ->one();
        }

        $number = Contract::find()
            ->where(['player_id' => $this->id])
            ->andWhere(['season_id' => $seasonID->id])
            ->one();

        return isset($number->number) ? $number->number : '-';
    }
}
