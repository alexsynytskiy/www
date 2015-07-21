<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\user\models\User;

/**
 * This is the model class for table "claims".
 *
 * @property integer $id
 * @property integer $comment_id
 * @property integer $user_id
 * @property integer $comment_author
 * @property string $message
 * @property string $created_at
 * @property string $updated_at
 */
class Claim extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'claims';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'comment_author', 'user_id'], 'required'],
            [['comment_id', 'user_id', 'comment_author'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['message'], 'string', 'max' => 255],
            [['comment_id', 'user_id'], 'unique', 'targetAttribute' => ['comment_id', 'user_id'], 'message' => 'Пользователь уже жаловался на данный комментарий']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment_id' => 'Комментарий',
            'user_id' => 'Пользователь',
            'comment_author' => 'Автор комментария',
            'message' => 'Сообщение',
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'comment_author']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comment::className(), ['id' => 'comment_id']);
    }
}
