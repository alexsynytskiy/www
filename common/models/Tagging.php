<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "taggings".
 *
 * @property integer $id
 * @property integer $tag_id
 * @property integer $taggable_id
 * @property string $taggable_type
 *
 * @property Tags $tag
 */
class Tagging extends \yii\db\ActiveRecord
{
    /**
     * @var string taggable types
     */
    const TAGGABLE_ALBUM   = 'album';
    const TAGGABLE_BANNER  = 'banner';
    const TAGGABLE_COACH   = 'coach';
    const TAGGABLE_COMMAND = 'command';
    const TAGGABLE_COUNTRY = 'country';
    const TAGGABLE_PLAYER  = 'player';
    const TAGGABLE_POST    = 'post';
    const TAGGABLE_USER    = 'user';
    const TAGGABLE_VIDEO   = 'video';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'taggings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'taggable_id'], 'integer'],
            [['taggable_type'], 'string', 'max' => 255]
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
            'taggable_id' => 'ID материала',
            'taggable_type' => 'Тип материала',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }
}
