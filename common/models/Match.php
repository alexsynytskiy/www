<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "matches".
 *
 * @property integer $id
 * @property integer $is_visible
 * @property integer $championship_id
 * @property integer $command_home_id
 * @property integer $command_guest_id
 * @property integer $stadium_id
 * @property integer $season_id
 * @property string $round
 * @property string $date
 * @property integer $arbiter_main_id
 * @property integer $arbiter_assistant_1_id
 * @property integer $arbiter_assistant_2_id
 * @property integer $arbiter_reserve_id
 * @property integer $home_shots
 * @property integer $guest_shots
 * @property integer $home_shots_in
 * @property integer $guest_shots_in
 * @property integer $home_offsides
 * @property integer $guest_offsides
 * @property integer $home_corners
 * @property integer $guest_corners
 * @property integer $home_fouls
 * @property integer $guest_fouls
 * @property integer $home_yellow_cards
 * @property integer $guest_yellow_cards
 * @property integer $home_red_cards
 * @property integer $guest_red_cards
 * @property integer $home_goals
 * @property integer $guest_goals
 * @property integer $comments_count
 * @property string $created_at
 * @property string $updated_at
 * @property integer $championship_part_id
 * @property integer $league_id
 * @property integer $is_finished
 * @property string $announcement
 *
 * @property Compositions[] $compositions
 * @property MatchEvents[] $matchEvents
 * @property Championships $championship
 * @property Arbiters $arbiterAssistant2
 * @property Leagues $league
 * @property ChampionshipParts $championshipPart
 * @property Commands $commandHome
 * @property Commands $commandGuest
 * @property Stadia $stadium
 * @property Seasons $season
 * @property Arbiters $arbiterMain
 * @property Arbiters $arbiterReserve
 * @property Arbiters $arbiterAssistant1
 */
class Match extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'matches';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_visible', 'championship_id', 'command_home_id', 'command_guest_id', 'stadium_id', 'season_id', 'arbiter_main_id', 'arbiter_assistant_1_id', 'arbiter_assistant_2_id', 'arbiter_reserve_id', 'home_shots', 'guest_shots', 'home_shots_in', 'guest_shots_in', 'home_offsides', 'guest_offsides', 'home_corners', 'guest_corners', 'home_fouls', 'guest_fouls', 'home_yellow_cards', 'guest_yellow_cards', 'home_red_cards', 'guest_red_cards', 'home_goals', 'guest_goals', 'comments_count', 'championship_part_id', 'league_id', 'is_finished'], 'integer'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['announcement'], 'string'],
            [['round'], 'string', 'max' => 50],

            //required
            [['championship_id', 'command_home_id', 'command_guest_id', 'stadium_id', 'season_id', 'arbiter_main_id', 'arbiter_assistant_1_id', 'arbiter_assistant_2_id', 'arbiter_reserve_id', 'championship_part_id', 'league_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                     => 'ID',
            'is_visible'             => 'Видимый',
            'championship_id'        => 'Championship ID',
            'command_home_id'        => 'Command Home ID',
            'command_guest_id'       => 'Command Guest ID',
            'stadium_id'             => 'Stadium ID',
            'season_id'              => 'Season ID',
            'round'                  => 'Round',
            'date'                   => 'Date',
            'arbiter_main_id'        => 'Arbiter Main ID',
            'arbiter_assistant_1_id' => 'Arbiter Assistant 1 ID',
            'arbiter_assistant_2_id' => 'Arbiter Assistant 2 ID',
            'arbiter_reserve_id'     => 'Arbiter Reserve ID',
            'home_shots'             => 'Home Shots',
            'guest_shots'            => 'Guest Shots',
            'home_shots_in'          => 'Home Shots In',
            'guest_shots_in'         => 'Guest Shots In',
            'home_offsides'          => 'Home Offsides',
            'guest_offsides'         => 'Guest Offsides',
            'home_corners'           => 'Home Corners',
            'guest_corners'          => 'Guest Corners',
            'home_fouls'             => 'Home Fouls',
            'guest_fouls'            => 'Guest Fouls',
            'home_yellow_cards'      => 'Home Yellow Cards',
            'guest_yellow_cards'     => 'Guest Yellow Cards',
            'home_red_cards'         => 'Home Red Cards',
            'guest_red_cards'        => 'Guest Red Cards',
            'home_goals'             => 'Home Goals',
            'guest_goals'            => 'Guest Goals',
            'comments_count'         => 'Comments Count',
            'created_at'             => 'Created At',
            'updated_at'             => 'Updated At',
            'championship_part_id'   => 'Championship Part ID',
            'league_id'              => 'League ID',
            'is_finished'            => 'Is Finished',
            'announcement'           => 'Announcement',
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
    public function getCompositions()
    {
        return $this->hasMany(Composition::className(), ['match_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatchEvents()
    {
        return $this->hasMany(MatchEvent::className(), ['match_id' => 'id']);
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
    public function getArbiterAssistant2()
    {
        return $this->hasOne(Arbiter::className(), ['id' => 'arbiter_assistant_2_id']);
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
    public function getChampionshipPart()
    {
        return $this->hasOne(ChampionshipPart::className(), ['id' => 'championship_part_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommandHome()
    {
        return $this->hasOne(Command::className(), ['id' => 'command_home_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommandGuest()
    {
        return $this->hasOne(Command::className(), ['id' => 'command_guest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStadium()
    {
        return $this->hasOne(Stadium::className(), ['id' => 'stadium_id']);
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
    public function getArbiterMain()
    {
        return $this->hasOne(Arbiter::className(), ['id' => 'arbiter_main_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArbiterReserve()
    {
        return $this->hasOne(Arbiter::className(), ['id' => 'arbiter_reserve_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArbiterAssistant1()
    {
        return $this->hasOne(Arbiter::className(), ['id' => 'arbiter_assistant_1_id']);
    }
}
