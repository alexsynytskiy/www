<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use amnah\yii2\user\models\User;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property string $content
 * @property string $created_at
 * @property integer $commentable_id
 * @property string $commentable_type
 * @property integer $user_id
 * @property integer $parent_id
 *
 * @property Users $user
 * @property Comment $parent
 * @property Comment[] $comments
 */
class Comment extends ActiveRecord
{
    /**
     * @var string commentable types
     */
    const COMMENTABLE_MATCH    = 'match';
    const COMMENTABLE_PHOTO    = 'photo';
    const COMMENTABLE_POST     = 'post';
    const COMMENTABLE_TRANSFER = 'transfer';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['created_at'], 'safe'],
            [['commentable_id', 'user_id', 'parent_id'], 'integer'],
            [['commentable_type'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Содержимое',
            'created_at' => 'Создано',
            'commentable_id' => 'ID сущности',
            'commentable_type' => 'Тип сущности',
            'user_id' => 'Пользователь',
            'parent_id' => 'Родительский ID',
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
     * Get comment content
     *
     * @return string
     */
    public function getContent()
    {
        return strip_tags($this->content);
    }

    /**
     * Get the link to commentable entity
     *
     * @return string
     */
    public function getCommentableLink()
    {
        return yii\helpers\Html::a($this->getCommentableType().'/'.$this->commentable_id,[$this->getCommentableUrl()]);
    }

    /**
     * Get the url to commentable entity
     *
     * @return string
     */
    public function getCommentableUrl()
    {
        return yii\helpers\Url::to('/'.$this->getCommentableType().'/'.$this->commentable_id);
    }

    /**
     * Get the url to commentable entity
     *
     * @return string
     */
    public function getCommentableType()
    {
        return strtolower($this->commentable_type);
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
    public function getParent()
    {
        return $this->hasOne(Comment::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['parent_id' => 'id']);
    }
}
