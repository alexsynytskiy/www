<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "commands".
 *
 * @property integer $id
 * @property string $name
 * @property string $info
 * @property integer $country_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Carreers[] $carreers
 * @property Compositions[] $compositions
 * @property Contracts[] $contracts
 * @property Matches[] $matches
 * @property Memberships[] $memberships
 * @property Tournaments[] $tournaments
 * @property Transfers[] $transfers
 */
class Team extends ActiveRecord
{
    /**
     * @var int team for matches
     */
    const TEAM_DK_FIRST = 213;
    /**
     * @var int team for matches
     */
    const TEAM_DK_FIRST_FULL_NAME = 1;
    /**
      * @var int team for matches
     */
    const TEAM_DK_M = 616;
    /**
      * @var int team for matches
     */
    const TEAM_DK2 = 8;
    /**
      * @var int team for matches
     */
    const TEAM_U19 = 878;
    /**
      * @var int team for matches
     */
    const TEAM_UKRAINE = 7;
    /**
      * @var int team for matches
     */
    const TEAM_UKRAINE_M = 117;
    /**
      * @var int team for matches
     */
    const TEAM_DK_KIDS = 221;
    /**
      * @var int team for matches
     */
    const TEAM_DK3 = 9;

    public static function getTeamsConstants()
    {
        $teamsConstants = [];
        
        $teamsConstants[] = self::TEAM_DK_FIRST;
        $teamsConstants[] = self::TEAM_DK_FIRST_FULL_NAME;
        $teamsConstants[] = self::TEAM_DK_M;
        $teamsConstants[] = self::TEAM_DK2;
        $teamsConstants[] = self::TEAM_U19;
        $teamsConstants[] = self::TEAM_UKRAINE;
        $teamsConstants[] = self::TEAM_UKRAINE_M;
        $teamsConstants[] = self::TEAM_DK_KIDS;
        $teamsConstants[] = self::TEAM_DK3;
        
        return $teamsConstants;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'commands';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['info'], 'string'],
            [['country_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'info' => 'Информация',
            'country_id' => 'Страна',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
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
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarreers()
    {
        return $this->hasMany(Carreer::className(), ['command_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompositions()
    {
        return $this->hasMany(Composition::className(), ['command_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['command_from_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGuestMatches()
    {
        return $this->hasMany(Match::className(), ['command_guest_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHomeMatches()
    {
        return $this->hasMany(Match::className(), ['command_home_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberships()
    {
        return $this->hasMany(Membership::className(), ['command_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTournaments()
    {
        return $this->hasMany(Tournament::className(), ['command_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransfers()
    {
        return $this->hasMany(Transfer::className(), ['command_to_id' => 'id']);
    }
}
