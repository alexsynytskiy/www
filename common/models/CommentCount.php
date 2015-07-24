<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "comment_counts".
 *
 * @property integer $id
 * @property integer $commentable_id
 * @property string $commentable_type
 * @property integer $count
 */
class CommentCount extends \yii\db\ActiveRecord
{
    /**
     * @var string assetable types
     */
    const COMMENTABLE_ALBUM    = 'album';
    const COMMENTABLE_MATCH    = 'match';
    const COMMENTABLE_PHOTO    = 'photo';
    const COMMENTABLE_POST     = 'post';
    const COMMENTABLE_TRANSFER = 'transfer';
    const COMMENTABLE_VIDEO    = 'video';
    const COMMENTABLE_INQUIRER = 'inquirer';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment_counts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['commentable_id', 'commentable_type'], 'required'],
            [['commentable_id', 'count'], 'integer'],
            [['commentable_type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'commentable_id' => 'Commentable ID',
            'commentable_type' => 'Commentable Type',
            'count' => 'Count',
        ];
    }

    /**
     * Get count for commentable entity
     *
     * @param int $commentableId
     * @param string $commentableType
     *
     * @return int
     */
    public static function getCommentCount($commentableId, $commentableType)
    {
        $commentCount = self::find()
            ->where([
                'commentable_id' => $commentableId,
                'commentable_type' => $commentableType,
            ])
            ->one();
        if(isset($commentCount->id)) return $commentCount->count;
        return 0;
    }
}
