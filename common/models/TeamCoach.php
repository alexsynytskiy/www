<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "team_coaches".
 *
 * @property integer $id
 * @property integer $team_id
 * @property integer $season_id
 * @property integer $coach_id
 * @property integer $is_main
 */
class TeamCoach extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team_coaches';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_id', 'season_id', 'coach_id'], 'required'],
            [['team_id', 'season_id', 'coach_id', 'is_main'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_id' => 'Команда',
            'season_id' => 'Сезон',
            'coach_id' => 'Тренер',
            'is_main' => 'Основной',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeason()
    {
        return $this->hasOne(Season::className(), ['id' => 'season_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoach()
    {
        return $this->hasOne(Coach::className(), ['id' => 'coach_id']);
    }
}
