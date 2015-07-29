<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "relations".
 *
 * @property integer $id
 * @property integer $relationable_id
 * @property string $relationable_type
 * @property integer $parent_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $relation_type_id
 */
class Relation extends ActiveRecord
{
    /**
     * @var int type of relation
     */
    const RELATION_NEWS = 1;
    const RELATION_REPORT = 2;
    const RELATION_ONLINE = 3;

    /**
     * @var int relationable type
     */
    const RELATIONABLE_POST = 'Post';
    const RELATIONABLE_ALBUM = 'Album';
    const RELATIONABLE_VIDEO = 'Video';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relationable_id', 'parent_id', 'relation_type_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['relationable_type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'relationable_id' => 'Relationable ID',
            'relationable_type' => 'Relationable Type',
            'parent_id' => 'Parent ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'relation_type_id' => 'Тип связи',
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
     * Get all available regions
     * @return array
     */
    public static function dropdownRelations() {
        return [
            self::RELATION_NEWS   => self::getRelationHumanName(self::RELATION_NEWS),
            self::RELATION_REPORT => self::getRelationHumanName(self::RELATION_REPORT),
            self::RELATION_ONLINE => self::getRelationHumanName(self::RELATION_ONLINE),
        ];
    }

    /**
     * Get human region name
     * @return array
     */
    public static function getRelationHumanName($id) {
        $types = [
            self::RELATION_NEWS   => 'Новости и статьи',
            self::RELATION_REPORT => 'Отчет',
            self::RELATION_ONLINE => 'Онлайн',
        ];
        return isset($types[$id]) ? $types[$id] : $types[0];
    }

    /**
     * Get human region name for current model
     * @return array
     */
    public function getRelationName() {
        return self::getRelationHumanName($this->relation_type_id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatch()
    {
        return $this->hasOne(Match::className(), ['id' => 'parent_id']);
    }
}
