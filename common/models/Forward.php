<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "forwards".
 *
 * @property integer $id
 * @property integer $team_id
 * @property integer $season_id
 * @property integer $player_id
 * @property integer $goals
 * @property integer $penalty
 * @property integer $matches
 */
class Forward extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forwards';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goals', 'penalty', 'matches', 'player_id', 'team_id', 'season_id'], 'integer'],

            // required
            [['player_id', 'team_id', 'season_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'player_id' => 'Имя',
            'team_id' => 'Команда',
            'season_id' => 'Cезон',
            'goals' => 'Голов',
            'penalty' => 'Пенальти',
            'matches' => 'Матчей',
        ];
    }

    /**
     * @return string Player name
     */
    public function getName()
    {
        return isset($this->player) ? $this->player->name : $this->player_id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(Player::className(), ['id' => 'player_id']);
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
    public function getSeason()
    {
        return $this->hasOne(Season::className(), ['id' => 'season_id']);
    }
}
