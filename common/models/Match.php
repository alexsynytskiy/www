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
 * @property integer $arbiter_assistant_3_id
 * @property integer $arbiter_assistant_4_id
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
            [['is_visible', 'championship_id', 'command_home_id', 'command_guest_id', 'stadium_id', 'season_id', 'home_shots', 'guest_shots', 'home_shots_in', 'guest_shots_in', 'home_offsides', 'guest_offsides', 'home_corners', 'guest_corners', 'home_fouls', 'guest_fouls', 'home_yellow_cards', 'guest_yellow_cards', 'home_red_cards', 'guest_red_cards', 'home_goals', 'guest_goals', 'comments_count', 'championship_part_id', 'league_id', 'is_finished'], 'integer'],
            [['date', 'created_at', 'updated_at', 'arbiter_assistant_3_id', 'arbiter_assistant_4_id', 'arbiter_main_id', 'arbiter_assistant_1_id', 'arbiter_assistant_2_id', 'arbiter_reserve_id'], 'safe'],
            [['announcement'], 'string'],
            [['round'], 'string', 'max' => 50],

            //required
            [['championship_id', 'command_home_id', 'command_guest_id', 'stadium_id', 'season_id', 'championship_part_id', 'league_id'], 'required'],
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
            'championship_id'        => 'Турнир',
            'command_home_id'        => 'Хозяева',
            'command_guest_id'       => 'Гости',
            'stadium_id'             => 'Стадион',
            'season_id'              => 'Сезон',
            'round'                  => 'Тур',
            'date'                   => 'Дата',
            'arbiter_main_id'        => 'Главный арбитр',
            'arbiter_assistant_1_id' => 'Лайнсмен',
            'arbiter_assistant_2_id' => 'Лайнсмен',
            'arbiter_assistant_3_id' => 'Арбитр за воротами',
            'arbiter_assistant_4_id' => 'Арбитр за воротами',
            'arbiter_reserve_id'     => 'Резервный арбитр',
            'home_shots'             => 'Удары по воротам(хозяева)',
            'guest_shots'            => 'Удары по воротам(гости)',
            'home_shots_in'          => 'Удары в створ(хозяева)',
            'guest_shots_in'         => 'Удары в створ(гости)',
            'home_offsides'          => 'Офсайды(хозяева)',
            'guest_offsides'         => 'Офсайды(гости)',
            'home_corners'           => 'Угловые(хозяева)',
            'guest_corners'          => 'Угловые(гости)',
            'home_fouls'             => 'Фолы(хозяева)',
            'guest_fouls'            => 'Фолы(гости)',
            'home_yellow_cards'      => 'Жёлтые карточки(хозяева)',
            'guest_yellow_cards'     => 'Жёлтые карточки(гости)',
            'home_red_cards'         => 'Красные карточки(хозяева)',
            'guest_red_cards'        => 'Красные карточки(гости)',
            'home_goals'             => 'Голы(хозяева)',
            'guest_goals'            => 'Голы(гости)',
            'comments_count'         => 'Количество комментариев',
            'created_at'             => 'Создано',
            'updated_at'             => 'Обновлено',
            'championship_part_id'   => 'Этап турнира',
            'league_id'              => 'Лига',
            'is_finished'            => 'Завершён',
            'announcement'           => 'Где смотреть',
        ];
    }    
    
    /**
     * @return string match result for our teams of interest
     */
    public function checkMatchWinner()
    {
        $teamsConstants = Command::getTeamsConstants();
        
        if(isset($this->home_goals) && isset($this->guest_goals)) {
            if ($this->home_goals == $this->guest_goals) {
                return "yellow";
            }
            elseif (in_array($this->command_home_id, $teamsConstants)) {
                if($this->home_goals > $this->guest_goals) {
                    return "green";
                }
                if($this->home_goals < $this->guest_goals) {
                    return "red";
                }
            }
            elseif (in_array($this->command_guest_id, $teamsConstants)) {
                if($this->home_goals < $this->guest_goals) {
                    return "green";
                }
                if($this->home_goals > $this->guest_goals) {
                    return "red";
                }
            }
        }
    }
    
    /**
     * Get home team logo asset
     *     
     * @return Asset
     */
    public function getAssetHome()
    {
        return Asset::getAssets($this->command_home_id, Asset::ASSETABLE_COMMAND, NULL, true);
    }
    
     /**
     * Get guest team logo asset
     *
     * @return Asset
     */
    public function getAssetGuest()
    {
        return Asset::getAssets($this->command_guest_id, Asset::ASSETABLE_COMMAND, NULL, true);
    }

    /**
     * @return string match championshipPart name
     */
    public function getChampionshipPartName()
    {
        if(isset($this->championshipPart->name)) {
          return $this->championshipPart->name;
        } else {
          return '';
        }
    }

    /**
     * @return string match championshipPart name
     */
    public function getTournamentName()
    {
        $name = $this->championship->name;
        if($this->getChampionshipPartName() != '') {
          $name .= ', '.$this->getChampionshipPartName();
        } 
        return $name;
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
    public function getArbiterAssistant3()
    {
        return $this->hasOne(Arbiter::className(), ['id' => 'arbiter_assistant_3_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArbiterAssistant4()
    {
        return $this->hasOne(Arbiter::className(), ['id' => 'arbiter_assistant_4_id']);
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
