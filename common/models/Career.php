<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "carreers".
 *
 * @property integer $id
 * @property integer $player_id
 * @property integer $league_id
 * @property integer $season_id
 * @property integer $command_id
 * @property integer $championship_matches
 * @property integer $championship_goals
 * @property integer $cup_matches
 * @property integer $cup_goals
 * @property integer $euro_matches
 * @property integer $euro_goals
 * @property double $avg_mark
 * @property integer $goal_passes
 * @property string $created_at
 * @property string $updated_at
 */
class Career extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'carreers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['player_id', 'league_id', 'season_id', 'command_id', 'championship_matches', 'championship_goals', 'cup_matches', 'cup_goals', 'euro_matches', 'euro_goals', 'goal_passes'], 'integer'],
            [['avg_mark'], 'number'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'player_id' => 'Игрок',
            'league_id' => 'Лига',
            'season_id' => 'Сезон',
            'command_id' => 'Команда',
            'championship_matches' => 'Матчей в чемпионате',
            'championship_goals' => 'Голов в чемпионате',
            'cup_matches' => 'Матчей в кубке',
            'cup_goals' => 'Голов в кубке',
            'euro_matches' => 'Матчей в еврокубках',
            'euro_goals' => 'Голов в еврокубках',
            'avg_mark' => 'Общая оценка',
            'goal_passes' => 'Ассисты',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
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
    public function getLeague()
    {
        return $this->hasOne(League::className(), ['id' => 'league_id']);
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
        return $this->hasOne(Team::className(), ['id' => 'command_id']);
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
