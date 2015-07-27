<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "compositions".
 *
 * @property integer $id
 * @property integer $match_id
 * @property integer $contract_id
 * @property integer $is_substitution
 * @property integer $is_basis
 * @property integer $number
 * @property integer $is_captain
 * @property integer $command_id
 * @property string $contract_type
 */
class Composition extends ActiveRecord
{
    /**
     * @var string contract types
     */
    const CONTRACT_TYPE = 'contract';
    const MEMBERSHIP_TYPE = 'membership';

    public $amplua_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'compositions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'contract_id', 'is_substitution', 'is_basis', 'number', 'is_captain', 'command_id', 'amplua_id'], 'integer'],
            [['contract_type'], 'string', 'max' => 255],

            // required
            [['match_id', 'contract_id', 'command_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'match_id' => 'Матч',
            'contract_id' => 'ID контракта',
            'is_substitution' => 'Замена',
            'is_basis' => 'Основа',
            'number' => 'Номер',
            'is_captain' => 'Капитан',
            'command_id' => 'Команда',
            'contract_type' => 'Тип контракта',
            'amplua_id' => 'Амплуа',
        ];
    }

    /**
     * @return string Player name
     */
    public function getName(){
        if(isset($this->contract)){
            $player = $this->contract->player;
            return $player->name;
        }
        return 'Имя игрока';
    }

    /**
     * @return array players sorted from gk to fwd
     */
    public static function getSquadSort($matchID) {
        $playersArray = self::find()
            ->where([
                'match_id' => $matchID,
            ])
            ->all();
        return self::sortPlayers($playersArray);
    }

    /**
     * @return string normalize contact type
     */
    public function getContractType(){
        return strtolower($this->contract_type);
    }

    /**
     * Get amplua id from contract
     * @return int 
     */
    public function getAmpluaId(){
        if(isset($this->contract->amplua)) {
            return $this->contract->amplua->id;
        } else return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'command_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatch()
    {
        return $this->hasOne(Match::className(), ['id' => 'match_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContract()
    {
        if($this->getContractType() == self::CONTRACT_TYPE) {
            return $this->hasOne(Contract::className(), ['id' => 'contract_id']);
        } elseif($this->getContractType() == self::MEMBERSHIP_TYPE) {
            return $this->hasOne(Membership::className(), ['id' => 'contract_id']);
        } else return null;
    }

    /**
     * Sort players in composition
     * @param array $players Array of Composition
     * @return array Sorted players
     */
    public static function sortPlayers($players)
    {
        usort($players, 'self::ampluaCmp');
        return $players;
    }

    /**
     * Comparing a weight of blocks in columns
     * @param array $a
     * @param array $b
     * @return int Result of comparing
     */
    private static function ampluaCmp($a, $b)
    {
        if(!isset($a->contract)) return -1;
        if(!isset($b->contract)) return -1;
        if ($a->contract->amplua_id == $b->contract->amplua_id) {
            return 0;
        }
        return ($a->contract->amplua_id < $b->contract->amplua_id) ? -1 : 1;
    }
}
