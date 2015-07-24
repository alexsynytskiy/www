<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "banned_ips".
 *
 * @property integer $id
 * @property string $title
 * @property string $start_ip_num_value
 * @property string $end_ip_num_value
 * @property integer $is_active
 * @property integer $start_ip_num
 * @property integer $end_ip_num
 * @property string $created_at
 * @property string $updated_at
 * 12.12.12.12
 */
class BannedIP extends ActiveRecord
{
    public $ip_address;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banned_ips';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'start_ip_num', 'end_ip_num'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['start_ip_num_value', 'end_ip_num_value', 'ip_address'], 'match', 'pattern' => '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/i'],
            [['start_ip_num_value', 'end_ip_num_value', 'ip_address'], 'string', 'max' => 50],
            [['title', 'start_ip_num_value'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Описание',
            'start_ip_num_value' => 'Начальный IP',
            'end_ip_num_value' => 'Конечный IP',
            'is_active' => 'Активно',
            'start_ip_num' => 'Start IP',
            'end_ip_num' => 'End IP',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'ip_address' => 'IP адрес',
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
}
