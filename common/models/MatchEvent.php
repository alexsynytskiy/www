<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "match_events".
 *
 * @property integer $id
 * @property integer $match_id
 * @property integer $match_event_type_id
 * @property integer $composition_id
 * @property integer $minute
 * @property string $notes
 * @property string $created_at
 * @property string $updated_at
 * @property integer $substitution_id
 * @property integer $additional_minute
 * @property integer $is_hidden
 * @property integer $position
 */
class MatchEvent extends ActiveRecord
{
    /**
     * @var string Goal type id
     */
    const GOAL = 1;
    /**
     * @var string Goal type id
     */
    const GOAL_PENALTY = 6;
    /**
     * @var string Goal type id
     */
    const AUTOGOAL = 9;
    /**
     * @var string substitution type id
     */
    const SUBSTITUTION = 15;
    /**
     * @var string yellow card type id
     */
    const YELLOWCARD = 2;
    /**
     * @var string red card type id
     */
    const REDCARD = 3;
    /**
     * @var string red card type id
     */
    const SECONDYELLOW = 19;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'match_events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'match_event_type_id', 'composition_id', 'minute', 'substitution_id', 'additional_minute', 'is_hidden', 'position'], 'integer'],
            [['notes'], 'string'],
            [['created_at', 'updated_at'], 'safe']
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'match_id' => 'Match ID',
            'match_event_type_id' => 'Событие матча',
            'composition_id' => 'Игрок',
            'minute' => 'Минута',
            'notes' => 'Комментарий',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'substitution_id' => 'Игрок на замену',
            'additional_minute' => 'Дополнительное время',
            'is_hidden' => 'Скрыт',
            'position' => 'Position',
        ];
    }

    /**
     * Get goal ids
     * @return array
     */
    public static function getGoalTypes()
    {
        return [
            self::GOAL,
            self::GOAL_PENALTY,
            self::AUTOGOAL,
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            if(in_array($this->match_event_type_id, self::getGoalTypes())) {
                $match = Match::findOne($this->match_id);
                $contract = Composition::findOne($this->composition_id);
                if(isset($match) && isset($contract)) {
                    if($contract->command_id == $match->command_home_id){
                        if($this->match_event_type_id == self::AUTOGOAL){
                            $match->guest_goals++;
                            $match->guest_shots++;
                            $match->guest_shots_in++;
                        } else {
                            $match->home_goals++;
                            $match->home_shots++;
                            $match->home_shots_in++;
                        }
                    } elseif($contract->command_id == $match->command_guest_id) {
                        if($this->match_event_type_id == self::AUTOGOAL){
                            $match->home_goals++;
                            $match->home_shots++;
                            $match->home_shots_in++;
                        } else {
                            $match->guest_goals++;
                            $match->guest_shots++;
                            $match->guest_shots_in++;
                        }
                    }
                    $match->save();
                }
            }
        }
        return parent::beforeSave($insert);
    }
    public function getTime() {
        $additionalMinutes = $this->additional_minute ? ' + '.$this->additional_minute.'"' : '';
        $minute = $this->minute ? $this->minute.'" ' : '';
        return $minute.$additionalMinutes;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatch()
    {
        return $this->hasOne(Match::className(), ['id' => 'match_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatchEventType()
    {
        return $this->hasOne(MatchEventType::className(), ['id' => 'match_event_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComposition()
    {
        return $this->hasOne(Composition::className(), ['id' => 'composition_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubstitution()
    {
        return $this->hasOne(Composition::className(), ['id' => 'substitution_id']);
    }
}
