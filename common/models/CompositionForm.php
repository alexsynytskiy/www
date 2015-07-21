<?php
namespace common\models;

use Yii;
use yii\helpers\Json;

/**
 * CommentForm represents the model behind the adding new comments form.
 */
class CompositionForm extends Composition
{
    /**
     * List of home team contract or membership ids
     * @var array
     */
    public $homePlayers;

    /**
     * List of guest team contract or membership ids
     * @var array
     */
    public $guestPlayers;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'contract_id', 'is_substitution', 'is_basis', 'number', 'is_captain', 'command_id'], 'integer'],
            [['contract_type'], 'string', 'max' => 255],
            [['homeContracts', 'guestContracts'], 'safe'],

            // required
            [['match_id', 'contract_id', 'command_id'], 'required'],
        ];
    }

    /**
     * Init list of established contract ids
     * @return void
     */
    public function initPlayers($teamHomeId, $teamGuestId)
    {
        $composition = (new \yii\db\Query())
            ->select(['contract_id'])
            ->from($this->tableName())
            ->where([
                'match_id' => $this->match_id,
                'command_id' => $teamHomeId,
            ])->all();
        $ids = [];
        foreach ($composition as $data) {
            $ids[] = $data['contract_id'];
        }
        $this->homePlayers = Json::encode($ids);

        $composition = (new \yii\db\Query())
            ->select(['contract_id'])
            ->from($this->tableName())
            ->where([
                'match_id' => $this->match_id,
                'command_id' => $teamGuestId,
            ])->all();
        $ids = [];
        foreach ($composition as $data) {
            $ids[] = $data['contract_id'];
        }
        $this->guestPlayers = Json::encode($ids);
    }
}