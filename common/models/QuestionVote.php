<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\user\models\User;

/**
 * This is the model class for table "question_users".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $user_id
 */
class QuestionVote extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'user_id'], 'required'],
            [['question_id', 'user_id'], 'integer'],
            [['question_id', 'user_id'], 'unique', 'targetAttribute' => ['question_id', 'user_id'], 'message' => 'The combination of Question ID and User ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_id' => 'ID Вопроса',
            'user_id' => 'Пользователь',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }
}
