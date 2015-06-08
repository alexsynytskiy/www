<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "leagues".
 *
 * @property integer $id
 * @property string $name
 * @property string $abr
 *
 * @property Carreers[] $carreers
 * @property Matches[] $matches
 * @property Tournaments[] $tournaments
 */
class League extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leagues';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'abr'], 'string', 'max' => 255]
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
            'abr' => 'Аббревиатура',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarreers()
    {
        return $this->hasMany(Carreer::className(), ['league_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatches()
    {
        return $this->hasMany(Match::className(), ['league_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTournaments()
    {
        return $this->hasMany(Tournament::className(), ['league_id' => 'id']);
    }
}
