<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\user\models\User;
use yii\helpers\Url;

/**
 * CommentForm represents the model behind the adding new comments form.
 */
class CommentForm extends Comment
{

    public function rules()
    {
        return [
            [['id', 'commentable_id', 'user_id', 'parent_id'], 'integer'],
            [['created_at'], 'safe'],
            [['commentable_type'], 'string', 'max' => 15],
            [['content'], 'string'],

            //required
            [['commentable_id', 'commentable_type', 'user_id', 'content'], 'required'],
        ];
    }
}