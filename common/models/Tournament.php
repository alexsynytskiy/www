<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tournaments".
 *
 * @property integer $id
 * @property integer $command_id
 * @property integer $championship_id
 * @property integer $season_id
 * @property integer $played
 * @property integer $won
 * @property integer $draw
 * @property integer $lost
 * @property integer $goals_for
 * @property integer $goals_against
 * @property integer $points
 * @property integer $penalty_points
 * @property string $created_at
 * @property string $updated_at
 * @property double $fair_play
 * @property integer $league_id
 */
class Tournament extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tournaments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['command_id', 'championship_id', 'season_id', 
                'played', 'won', 'draw', 'lost', 'goals_for', 
                'goals_against', 'points', 'league_id', 'penalty_points',
                'weight'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['fair_play'], 'number'],

            // required
            [['command_id', 'championship_id', 'season_id', 'league_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'command_id' => 'Команда',
            'championship_id' => 'Чемпионат',
            'league_id' => 'Лига',
            'season_id' => 'Сезон',
            'played' => 'Сыграно',
            'won' => 'Побед',
            'draw' => 'Ничьи',
            'lost' => 'Поражений',
            'goals_for' => 'Забитых голов',
            'goals_against' => 'Пропущеных голов',
            'points' => 'Очков',
            'penalty_points' => 'Штрафных очков',
            'weight' => 'Вес',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'fair_play' => 'Честная игра',
            'team.name' => 'Команда',
            'league.name' => 'Лига',
            'season.name' => 'Сезон',
            'championship.name' => 'Чемпионат',
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
     * Sorting teams in tournament
     * @param array $teams
     * @return array Sorted array
     */
    public static function sort($teams) {
        usort($teams,'self::tournamentCmp');
        return $teams;
    }

    /**
     * Comparing teams in tournament
     * @param array $teamA
     * @param array $teamB
     * @return int Result of comparing
     */
    private static function tournamentCmp($teamA, $teamB)
    {
        $teamAGoals = $teamA->goals_for - $teamA->goals_against;
        $teamBGoals = $teamB->goals_for - $teamB->goals_against;
        if ($teamA->points == $teamB->points && $teamAGoals == $teamBGoals) {
            return 0;
        }
        if($teamA->points < $teamB->points) return 1;
        elseif($teamA->points > $teamB->points) return -1;
        else {
            return ($teamAGoals < $teamBGoals) ? 1 : -1;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->team->name.' ('.$this->season->name.')';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'command_id']);
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
    public function getLeague()
    {
        return $this->hasOne(League::className(), ['id' => 'league_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChampionship()
    {
        return $this->hasOne(Championship::className(), ['id' => 'championship_id']);
    }
}
