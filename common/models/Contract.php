<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "contracts".
 *
 * @property integer $id
 * @property integer $command_id
 * @property integer $season_id
 * @property integer $amplua_id
 * @property string $contractable_type
 * @property integer $player_id
 * @property integer $number
 * @property integer $command_from_id
 * @property integer $year_from
 * @property integer $year_till
 * @property integer $matches
 * @property integer $goals
 * @property integer $is_active
 * @property string $debut
 * @property string $created_at
 * @property string $updated_at
 */
class Contract extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contracts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['command_id', 'season_id', 'amplua_id', 'player_id', 'number', 'command_from_id', 'year_from', 'year_till', 'matches', 'goals', 'is_active'], 'integer'],
            [['debut', 'created_at', 'updated_at'], 'safe'],

            // required
            [['command_id', 'season_id', 'amplua_id'], 'required'],
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
            'season_id' => 'Сезон',
            'amplua_id' => 'Амплуа',
            'player_id' => 'Игрок',
            'number' => 'Номер',
            'command_from_id' => 'Из команды',
            'year_from' => 'Год начала',
            'year_till' => 'Год конца',
            'matches' => 'Матчей',
            'goals' => 'Голов',
            'is_active' => 'Активно',
            'debut' => 'Дебют',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
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
     * @return string Player name
     */
    public function getName()
    {
        return $this->player->name;
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
     * @return \yii\db\ActiveQuery
     */
    public function getTeamFrom()
    {
        return $this->hasOne(Team::className(), ['id' => 'command_from_id']);
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
    public function getAmplua()
    {
        return $this->hasOne(Amplua::className(), ['id' => 'amplua_id']);
    }
}
