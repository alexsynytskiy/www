<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transfers".
 *
 * @property integer $id
 * @property integer $season_id
 * @property integer $transfer_type_id
 * @property integer $player_id
 * @property integer $probability
 * @property integer $command_from_id
 * @property integer $command_to_id
 * @property string $sum
 * @property integer $is_active
 * @property string $clubs
 * @property string $others
 * @property string $contract_date
 * @property string $created_at
 * @property string $updated_at
 */
class Transfer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transfers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['season_id', 'transfer_type_id', 'player_id', 'probability', 'command_from_id', 'command_to_id', 'is_active'], 'integer'],
            [['contract_date', 'created_at', 'updated_at'], 'safe'],
            [['sum', 'clubs', 'others'], 'string', 'max' => 255],

            // required 
            [['season_id','transfer_type_id','player_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'season_id' => 'Сезон',
            'transfer_type_id' => 'Тип трансфера',
            'player_id' => 'Игрок',
            'probability' => 'Вероятность',
            'command_from_id' => 'Из команды',
            'command_to_id' => 'В команду',
            'clubs' => 'Клубы',
            'sum' => 'Сумма',
            'is_active' => 'Активно',
            'others' => 'Другое',
            'contract_date' => 'Дата контракта',
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
     * @inheritdoc
     */
    public function afterDelete()
    {
        Comment::deleteAll(['commentable_type' => Comment::COMMENTABLE_TRANSFER ,'commentable_id' => $this->id]);
        CommentCount::deleteAll(['commentable_type' => CommentCount::COMMENTABLE_TRANSFER ,'commentable_id' => $this->id]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        $name = $this->player->firstname.' '.$this->player->lastname;
        $teamFromName = isset($this->teamFrom) ? $this->teamFrom->name : '';
        $teamToName = isset($this->teamTo) ? $this->teamTo->name : '';
        $name .= ' ('.$teamFromName.' > '.$teamToName.')';
        return $name;
    }

    /**
     * @return string
     */
    public function getTransferTypeAbr()
    {
        $abr = 'none';
        if(isset($this->transferType)){
            if($this->transferType->id == 1) $abr = 'rent';
            if($this->transferType->id == 2) $abr = 'sell';
            if($this->transferType->id == 3) $abr = 'buy';
        }
        return $abr;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return \yii\helpers\Url::to(['/site/transfer/', 'id' => $this->id]);
    }

    /**
     * Get amount of photos in album
     * @return int
     */
    public function getCommentsCount() {
        return CommentCount::getCommentCount($this->id, CommentCount::COMMENTABLE_TRANSFER);
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
    public function getSeason()
    {
        return $this->hasOne(Season::className(), ['id' => 'season_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransferType()
    {
        return $this->hasOne(TransferType::className(), ['id' => 'transfer_type_id']);
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
    public function getTeamTo()
    {
        return $this->hasOne(Team::className(), ['id' => 'command_to_id']);
    }

}
