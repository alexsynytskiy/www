<?php

namespace common\modules\user\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $create_time
 * @property string  $update_time
 * @property string  $full_name
 *
 * @property User    $user
 */
class Profile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return static::getDb()->tablePrefix . "profile";
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['user_id'], 'required'],
            // [['user_id'], 'integer'],
            // [['create_time', 'update_time'], 'safe'],
            [['description'], 'string'],
            [['full_name'], 'filter', 'filter' => 'trim'],
            ['full_name', 'string', 
                'min' => 3, 'tooShort' => '{attribute} должно содержать минимум {min} символа',
                'max' => 30, 'tooLong' => '{attribute} должно содержать максимум {max} символов'
            ],
            [['full_name'], 'required', 'message' => 'Пожалуйста, введите {attribute}'],
            [['full_name'], 'match', 
                'pattern' => '@^[а-яА-ЯёЁa-zA-Z0-9 _\-]+$@u',
                'message' => '{attribute} введено не правильно'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('user', 'ID'),
            'user_id'     => Yii::t('user', 'User ID'),
            'create_time' => Yii::t('user', 'Create Time'),
            'update_time' => Yii::t('user', 'Update Time'),
            'full_name'   => 'Имя',
            'description'   => Yii::t('user', 'Описание'),
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
                    ActiveRecord::EVENT_BEFORE_INSERT => 'create_time',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
                ],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        $user = Yii::$app->getModule("user")->model("User");
        return $this->hasOne($user::className(), ['id' => 'user_id']);
    }

    /**
     * Set user id
     *
     * @param int $userId
     * @return static
     */
    public function setUser($userId)
    {
        $this->user_id = $userId;
        return $this;
    }
}