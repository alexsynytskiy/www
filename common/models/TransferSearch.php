<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Transfer;

/**
 * TransferSearch represents the model behind the search form about `common\models\Transfer`.
 */
class TransferSearch extends Transfer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'season_id', 'transfer_type_id', 'player_id', 'probability', 'command_from_id', 'command_to_id', 'is_active'], 'integer'],
            [['sum', 'clubs', 'others', 'contract_date', 'created_at', 'updated_at', 'teamFrom.name', 'teamTo.name', 'player.lastname'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'teamFrom.name',
            'teamTo.name',
            'player.lastname',
        ]);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Transfer::find();

        $transferTable = Transfer::tableName();
        // set up query with relation to `player.lastname`
        $playerTable = Player::tableName();
        // set up query with relation to `teamFrom.name` and `teamTo.name`
        $teamTable = Team::tableName();

        $query->joinWith(['player' => function($query) use ($playerTable) {
            $query->from(['player' => $playerTable]);
        }]);

        $query->joinWith(['teamFrom' => function($query) use ($teamTable) {
            $query->from(['teamFrom' => $teamTable]);
        }]);

        $query->joinWith(['teamTo' => function($query) use ($teamTable) {
            $query->from(['teamTo' => $teamTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        // enable sorting for the related columns
        $addSortAttributes = [
            "player.lastname",
            "teamFrom.name",
            "teamTo.name",
        ];
        foreach ($addSortAttributes as $addSortAttribute) {
            $dataProvider->sort->attributes[$addSortAttribute] = [
                'asc'   => [$addSortAttribute => SORT_ASC],
                'desc'  => [$addSortAttribute => SORT_DESC],
            ];
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            "{$transferTable}.id" => $this->id,
            'season_id' => $this->season_id,
            'transfer_type_id' => $this->transfer_type_id,
            'player_id' => $this->player_id,
            'probability' => $this->probability,
            'command_from_id' => $this->command_from_id,
            'command_to_id' => $this->command_to_id,
            'is_active' => $this->is_active,
            'contract_date' => $this->contract_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'sum', $this->sum])
            ->andFilterWhere(['like', 'clubs', $this->clubs])
            ->andFilterWhere(['like', 'others', $this->others])
            ->andFilterWhere(['like', 'teamFrom.name', $this->getAttribute('teamFrom.name')])
            ->andFilterWhere(['like', 'teamTo.name', $this->getAttribute('teamTo.name')])
            ->andFilterWhere(['like', 'player.lastname', $this->getAttribute('player.lastname')]);

        return $dataProvider;
    }
}
