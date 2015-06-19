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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'match_id' => 'Match ID',
            'match_event_type_id' => 'Match Event Type ID',
            'composition_id' => 'Composition ID',
            'minute' => 'Minute',
            'notes' => 'Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'substitution_id' => 'Substitution ID',
            'additional_minute' => 'Additional Minute',
            'is_hidden' => 'Is Hidden',
            'position' => 'Position',
        ];
    }

    public function getTime() {
        $additionalMinutes = $this->additional_minute ? ' + '.$this->additional_minute.'"' : '';
        return $this->minute.'" '.$additionalMinutes;
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
