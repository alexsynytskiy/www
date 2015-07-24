<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "selected_blogs".
 *
 * @property integer $id
 * @property integer $post_id
 */
class SelectedBlog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'selected_blogs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id'], 'required'],
            [['post_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Запись',
            'post.title' => 'Запись',
        ];
    }

    /**
     * After save update cache of blocks
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        $machineName = 'lastBlogPosts';
        $cacheBlock = CacheBlock::find()
            ->where(['machine_name' => $machineName])
            ->one();
        if(!isset($cacheBlock)){
            $cacheBlock = new CacheBlock();
            $cacheBlock->machine_name = $machineName;
        }
        $cacheBlock->content = SiteBlock::getBlogPosts(false);
        $cacheBlock->save();
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return string Post title
     */
    public function getName()
    {
        return isset($this->post) ? $this->post->title : $this->post_id;
    }
}
