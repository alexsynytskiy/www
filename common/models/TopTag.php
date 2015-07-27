<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "top_tags".
 *
 * @property integer $id
 * @property integer $tag_id
 */
class TopTag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'top_tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id'], 'required'],
            [['tag_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag_id' => 'Тег',
            'tag.name' => 'Тег',
        ];
    }

    public static function outTop6Links() {
        $topTags = TopTag::find()->limit(6)->all();
        foreach ($topTags as $tag) {
            $tagName = str_replace(' ', '+', $tag->name);
            ?>
            <a href="<?= Url::to(['site/search', 't' => $tagName]) ?>" class="tag"><?= $tag->name ?></a>
            <?php
        }
        ?> <a href="<?= Url::to(['site/tags']) ?>" class="tag">Все теги</a> <?php
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }

    /**
     * @return string Tag name
     */
    public function getName()
    {
        return isset($this->tag) ? $this->tag->name : $this->tag_id;
    }
}