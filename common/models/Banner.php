<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "banners".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property integer $size
 * @property integer $region
 * @property integer $weight
 * @property string $created_at
 * @property string $updated_at
 */
class Banner extends ActiveRecord
{
    const REGION_FIRST_COLUMN  = '1';
    const REGION_SECOND_COLUMN = '2';
    const REGION_THIRD_COLUMN  = '3';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content'], 'required'],
            [['content'], 'string'],
            [['size', 'region', 'weight'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
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
            'name' => 'Название',
            'content' => 'Содержимое',
            'size' => 'Размер',
            'region' => 'Регион',
            'weight' => 'Вес',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }
    /**
     * Get all available regions
     * @return array
     */
    public static function dropdownRegions() {
        return [
            0                          => self::getRegionHumanName(0),
            self::REGION_FIRST_COLUMN  => self::getRegionHumanName(self::REGION_FIRST_COLUMN),
            self::REGION_SECOND_COLUMN => self::getRegionHumanName(self::REGION_SECOND_COLUMN),
            self::REGION_THIRD_COLUMN  => self::getRegionHumanName(self::REGION_THIRD_COLUMN),
        ];
    }

    /**
     * Get human region name
     * @return array
     */
    public static function getRegionHumanName($id) {
        $regions = [
            0                          => 'Все колонки',
            self::REGION_FIRST_COLUMN  => 'Первая колонка',
            self::REGION_SECOND_COLUMN => 'Вторая колонка',
            self::REGION_THIRD_COLUMN  => 'Третья колонка',
        ];
        return isset($regions[$id]) ? $regions[$id] : $regions[0];
    }

    /**
     * Get human region name for current model
     * @return array
     */
    public function getRegionName() {
        return self::getRegionHumanName($this->region);
    }

}
