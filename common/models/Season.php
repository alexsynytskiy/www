<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "seasons".
 *
 * @property integer $id
 * @property string $name
 * @property int $window
 *
 * @property Carreers[] $carreers
 * @property Contracts[] $contracts
 * @property Matches[] $matches
 * @property Tournaments[] $tournaments
 * @property Transfers[] $transfers
 */
class Season extends ActiveRecord
{
    /**
     * @var int Summer window
     */
    const WINDOW_SUMMER = 0;
    /**
     * @var int Winter window
     */
    const WINDOW_WINTER = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seasons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255]
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarreers()
    {
        return $this->hasMany(Carreer::className(), ['season_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['season_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatches()
    {
        return $this->hasMany(Match::className(), ['season_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTournaments()
    {
        return $this->hasMany(Tournament::className(), ['season_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransfers()
    {
        return $this->hasMany(Transfer::className(), ['season_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForwards()
    {
        return $this->hasMany(Forward::className(), ['season_id' => 'id']);
    }
}
