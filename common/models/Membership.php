<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "memberships".
 *
 * @property integer $id
 * @property integer $command_id
 * @property integer $player_id
 * @property integer $number
 * @property integer $amplua_id
 * @property string $created_at
 * @property string $updated_at
 */
class Membership extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'memberships';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['command_id', 'player_id', 'number', 'amplua_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],

            // required
            [['command_id', 'player_id', 'amplua_id'], 'required'],
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
            'player_id' => 'Игрок',
            'number' => 'Номер',
            'amplua_id' => 'Амплуа',
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
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'command_id']);
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
