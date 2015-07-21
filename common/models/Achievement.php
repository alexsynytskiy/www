<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "achievements".
 *
 * @property integer $id
 * @property integer $player_id
 * @property string $name
 */
class Achievement extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'achievements';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['player_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['player_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'player_id' => 'Игрок',
            'name' => 'Достижение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(Player::className(), ['id' => 'player_id']);
    }
}
