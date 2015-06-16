<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Contract;

/**
 * ContractSearch represents the model behind the search form about `common\models\Contract`.
 */
class ContractSearch extends Contract
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'command_id', 'season_id', 'amplua_id', 'number', 'command_from_id', 'year_from', 'year_till', 'matches', 'goals', 'is_active'], 'integer'],
            [['debut', 'created_at', 'updated_at', 'teamFrom.name', 'player.lastname', 'team.name', 'season.name', 'amplua.name'], 'safe'],
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
            'team.name',
            'player.lastname',
            'season.name',
            'amplua.name',
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
        $query = Contract::find();

        $contractTable = Contract::tableName();
        $playerTable = Player::tableName();
        $teamTable = Team::tableName();
        $seasonTable = Season::tableName();
        $ampluaTable = Amplua::tableName();

        $query->joinWith(['player' => function($query) use ($playerTable) {
            $query->from(['player' => $playerTable]);
        }]);

        $query->joinWith(['team' => function($query) use ($teamTable) {
            $query->from(['team' => $teamTable]);
        }]);

        $query->joinWith(['teamFrom' => function($query) use ($teamTable) {
            $query->from(['teamFrom' => $teamTable]);
        }]);

        $query->joinWith(['season' => function($query) use ($seasonTable) {
            $query->from(['season' => $seasonTable]);
        }]);

        $query->joinWith(['amplua' => function($query) use ($ampluaTable) {
            $query->from(['amplua' => $ampluaTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $addSortAttributes = [
            "player.lastname",
            "teamFrom.name",
            "team.name",
            "season.name",
            "amplua.name",
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
            "{$contractTable}.id" => $this->id,
            'command_id' => $this->command_id,
            'season_id' => $this->season_id,
            'amplua_id' => $this->amplua_id,
            'number' => $this->number,
            'command_from_id' => $this->command_from_id,
            'year_from' => $this->year_from,
            'year_till' => $this->year_till,
            'matches' => $this->matches,
            'goals' => $this->goals,
            'is_active' => $this->is_active,
            'debut' => $this->debut,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'teamFrom.name', $this->getAttribute('teamFrom.name')])
            ->andFilterWhere(['like', 'team.name', $this->getAttribute('team.name')])
            ->andFilterWhere(['like', 'player.lastname', $this->getAttribute('player.lastname')])
            ->andFilterWhere(['like', 'season.name', $this->getAttribute('season.name')])
            ->andFilterWhere(['like', 'amplua.name', $this->getAttribute('amplua.name')]);

        return $dataProvider;
    }
}
