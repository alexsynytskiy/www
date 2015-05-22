<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "stadia".
 *
 * @property integer $id
 * @property string $name
 * @property integer $spectators
 * @property integer $country_id
 *
 * @property Matches[] $matches
 * @property Countries $country
 */
class Stadium extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stadia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spectators', 'country_id'], 'integer'],
            [['name', 'country_id'], 'required'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'spectators' => 'Вместимость',
            'country_id' => 'Страна',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatch()
    {
        return $this->hasMany(Match::className(), ['stadium_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }
}
