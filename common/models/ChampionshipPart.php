<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "championship_parts".
 *
 * @property integer $id
 * @property string $name
 * @property integer $championship_id
 *
 * @property Championships $championship
 * @property Matches[] $matches
 */
class ChampionshipPart extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'championship_parts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['championship_id'], 'integer'],
            [['name'], 'string', 'max' => 255]
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
            'championship_id' => 'Турнир',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChampionship()
    {
        return $this->hasOne(Championship::className(), ['id' => 'championship_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatches()
    {
        return $this->hasMany(Match::className(), ['championship_part_id' => 'id']);
    }
}
