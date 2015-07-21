<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\user\models\User;

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
class Vote extends ActiveRecord
{
    /**
     * @var string Types of voting
     */
    const VOTEABLE_PHOTO   = 'photo';
    const VOTEABLE_POST    = 'post';
    const VOTEABLE_COMMENT = 'comment';
    
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
            ['voteable_type', function ($attribute, $params) {
                $voteableTypeList = [
                    self::VOTEABLE_PHOTO,
                    self::VOTEABLE_POST,
                    self::VOTEABLE_COMMENT,
                ];
                if(!in_array(strtolower($this->voteable_type), $voteableTypeList)){
                    $this->addError('voteable_type',$this->voteable_type.' Ошибка в типе голоса');
                }
            }],

            // required
            [['vote', 'voteable_id', 'user_id'], 'required'],

            ['ip_address', 'unique', 'targetAttribute' => [
                'ip_address', 'user_id', 'voteable_id', 'voteable_type'
            ], 'message' => 'Голос уже учтен'],
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
    public function beforeValidate()
    {
        $this->vote = ($this->vote >= 1) ? 1 : 0;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $this->ip_address = (int)str_replace('.', '', $ip);

        return parent::beforeValidate();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /**
     * Get count of votes
     *
     * @return array Array of string
     */
    public static function getVotes($voteableId, $voteableType, $vote = 1)
    {
        return self::find()
            ->where([
                'voteable_id' => $voteableId,
                'voteable_type' => $voteableType,
                'vote' => $vote,
            ])->count();
    }

    /**
     * Get list of voteable types for creating dropdowns
     *
     * @return array Array of string
     */
    public static function voteableTypeDropdown()
    {
        static $dropdown;
        if ($dropdown === null) {

            $dropdown[self::VOTEABLE_COMMENT] = self::voteableTypeHumanName(self::VOTEABLE_COMMENT);
            $dropdown[self::VOTEABLE_PHOTO] = self::voteableTypeHumanName(self::VOTEABLE_PHOTO);
            $dropdown[self::VOTEABLE_POST] = self::voteableTypeHumanName(self::VOTEABLE_POST);

        }
        return $dropdown;
    }

    /**
     * Get voteable type human name
     *
     * @param integer $category_id
     * @return string
     */
    public static function voteableTypeHumanName($type)
    {
        $type = strtolower($type);
        if ($type == self::VOTEABLE_COMMENT) return 'Коммент';
        elseif ($type == self::VOTEABLE_PHOTO) return 'Фото';
        elseif ($type == self::VOTEABLE_POST) return 'Пост';

        return 'Не определено';
    }

    /**
     * Get current voteable type
     *
     * @return string
     */
    public function getVoteableType()
    {
        return self::voteableTypeHumanName($this->voteable_type);
    }

    /**
     * Get rating
     *
     * @param integer $id Voteable ID
     * @param string $type Voteable type
     * @return integer
     */
    public static function getRating($id, $type)
    {
        $countPos = self::find()
            ->where([
                'voteable_type' => $type,
                'voteable_id' => $id,
                'vote' => 1,
            ])->count();
        $countNeg = self::find()
            ->where([
                'voteable_type' => $type,
                'voteable_id' => $id,
                'vote' => 0,
            ])->count();
        return $countPos - $countNeg;
    }

    /**
     * Get user vote
     *
     * @param integer $id Voteable ID
     * @param string $type Voteable type
     * @return integer 
     */
    public static function getUserVote($id, $type)
    {
        if(Yii::$app->user->isGuest) return false;

        $vote = self::find()
            ->where([
                'voteable_type' => $type,
                'voteable_id' => $id,
                'user_id' => Yii::$app->user->id
            ])->one();

        if(!isset($vote->id)) return false;
        return $vote->vote == 1 ? 1 : -1;
    }
}
