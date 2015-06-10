<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ampluas".
 *
 * @property integer $id
 * @property string $name
 * @property string $abr
 * @property integer $line
 */
class Amplua extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ampluas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['line'], 'integer'],
            [['name', 'abr'], 'string', 'max' => 255]
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
            'abr' => 'Аббревиатура',
            'line' => 'Линия',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Player::className(), ['amplua_id' => 'id']);
    }
}
