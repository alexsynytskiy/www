<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sources".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 */
class Source extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sources';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
        ];
    }

    /**
     * Checking if some source already exist
     *
     * @param array $params Model properties
     * @return boolean
     */
    public function modelExist() {
        if(trim($this->name) == '') return false;
        if(trim($this->url) == '') return false;
        $count = self::find()
            ->where(['name' => $this->name, 'url' => $this->url])
            ->count();
        return $count ? true : false;
    }
}
