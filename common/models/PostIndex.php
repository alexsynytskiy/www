<?php

namespace common\models;

use Yii;

/**
 * This is the model class for index "post_index".
 *
 * @property integer $id
 * @property string $content
 */
class PostIndex extends \yii\sphinx\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function indexName()
    {
        return 'post_index';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'unique'],
            [['id'], 'integer'],
            [['content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
        ];
    }
}
