<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "questions".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parent_id
 * @property integer $voutes
 * @property integer $is_active
 * @property string $created_at
 * @property string $updated_at
 * @property integer $position
 * @property integer $is_multipart
 * @property integer $is_float
 * @property double $mark
 */
class Question extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'questions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'voutes', 'is_active', 'position', 'is_multipart', 'is_float'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['mark'], 'number'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'required'],
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'parent_id' => 'Parent ID',
            'voutes' => 'Голосов',
            'is_active' => 'Активно',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'position' => 'Порядок',
            'is_multipart' => 'Множественный выбор',
            'is_float' => 'С оценками',
            'mark' => 'Оценка',
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        Comment::deleteAll(['commentable_type' => Comment::COMMENTABLE_INQUIRER ,'commentable_id' => $this->id]);
        CommentCount::deleteAll(['commentable_type' => CommentCount::COMMENTABLE_INQUIRER ,'commentable_id' => $this->id]);
    }
}
