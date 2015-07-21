<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "main_info".
 *
 * @property string $name
 * @property string $title
 * @property string $content
 */
class MainInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'main_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'name'], 'string'],
            [['name'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'content' => 'Контент',
            'name' => 'Машинное имя',
        ];
    }
}
