<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "votes".
 *
 * @property integer $id
 * @property integer $vote
 * @property string $created_at
 * @property string $voteable_type
 * @property integer $voteable_id
 * @property integer $user_id
 * @property integer $ip_address
 *
 * @property Users $user
 */
class Vote extends \yii\db\ActiveRecord
{
    /**
     * @var string Types of voting
     */
    const ASSETABLE_PHOTO   = 'photo';
    const ASSETABLE_POST    = 'post';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'votes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vote', 'voteable_id', 'user_id', 'ip_address'], 'integer'],
            [['created_at'], 'safe'],
            [['voteable_type'], 'string', 'max' => 15],

            //required
            [['vote', 'voteable_id', 'user_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vote' => 'Голос',
            'created_at' => 'Создан',
            'voteable_type' => 'Тип материала',
            'voteable_id' => 'ID материала',
            'user_id' => 'ID пользователя',
            'ip_address' => 'IP Адрес',
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
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->vote = ($this->vote >= 1) ? 1 : 0;

        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
    
    public static function getVotes($voteableId, $voteableType, $vote = 1)
    {
        return self::find()
            ->where([
                'voteable_id' => $voteableId,
                'voteable_type' => $voteableType,
                'vote' => $vote,
            ])->count();
    }
}
