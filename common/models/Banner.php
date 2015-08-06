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
    const REGION_FIRST_COLUMN     = 1;
    const REGION_SECOND_COLUMN    = 2;
    const REGION_THIRD_COLUMN     = 3;
    const REGION_NEWS             = 4;
    const REGION_TOP              = 5;
    const REGION_BOTTOM           = 6;
    const REGION_TOP_THIRD_COLUMN = 7;
    const REGION_WRAPPER          = 8;
    const REGION_UNDER_NEWS       = 9;

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
            'size' => 'Большой размер баннера',
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
            self::REGION_FIRST_COLUMN     => self::getRegionHumanName(self::REGION_FIRST_COLUMN),
            self::REGION_SECOND_COLUMN    => self::getRegionHumanName(self::REGION_SECOND_COLUMN),
            self::REGION_THIRD_COLUMN     => self::getRegionHumanName(self::REGION_THIRD_COLUMN),
            self::REGION_NEWS             => self::getRegionHumanName(self::REGION_NEWS),
            self::REGION_TOP              => self::getRegionHumanName(self::REGION_TOP),
            self::REGION_BOTTOM           => self::getRegionHumanName(self::REGION_BOTTOM),
            self::REGION_TOP_THIRD_COLUMN => self::getRegionHumanName(self::REGION_TOP_THIRD_COLUMN),
            self::REGION_WRAPPER          => self::getRegionHumanName(self::REGION_WRAPPER),
            self::REGION_UNDER_NEWS       => self::getRegionHumanName(self::REGION_UNDER_NEWS),
        ];
    }

    /**
     * Get human region name
     * @return array
     */
    public static function getRegionHumanName($id) {
        $regions = [
            self::REGION_FIRST_COLUMN     => 'Первая колонка',
            self::REGION_SECOND_COLUMN    => 'Вторая колонка',
            self::REGION_THIRD_COLUMN     => 'Третья колонка',
            self::REGION_NEWS             => 'Новостной блок',
            self::REGION_TOP              => 'Верхний',
            self::REGION_BOTTOM           => 'Нижний',
            self::REGION_TOP_THIRD_COLUMN => 'Верхний в третьей',
            self::REGION_WRAPPER          => 'Подложка',
            self::REGION_UNDER_NEWS       => 'Под постом',
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
