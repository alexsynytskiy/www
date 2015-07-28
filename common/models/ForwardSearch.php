<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Forward;

/**
 * ForwardSearch represents the model behind the search form about `common\models\Forward`.
 */
class ForwardSearch extends Forward
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goals', 'penalty', 'matches', 'player_id', 'team_id', 'season_id'], 'integer'],
            [['team.name', 'player.lastname'], 'safe'],
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
            'team.name',
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
        $query = Forward::find();

        $forwardTable = Forward::tableName();
        $playerTable = Player::tableName();
        $teamTable = Team::tableName();

        $query->joinWith(['player' => function($query) use ($playerTable) {
            $query->from(['player' => $playerTable]);
        }]);

        $query->joinWith(['team' => function($query) use ($teamTable) {
            $query->from(['team' => $teamTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => ['defaultOrder' => [
                'goals' => SORT_DESC,
                'penalty' => SORT_DESC,
            ]]
        ]);

        // enable sorting for the related columns
        $addSortAttributes = [
            "player.lastname",
            "team.name",
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
            "{$forwardTable}id" => $this->id,
            'goals' => $this->goals,
            'penalty' => $this->penalty,
            'matches' => $this->matches,
            'team_id' => $this->team_id,
            'player_id' => $this->player_id,
            'season_id' => $this->season_id,
        ]);

        $query->andFilterWhere(['like', 'team.name', $this->getAttribute('team.name')])
            ->andFilterWhere(['like', 'player.lastname', $this->getAttribute('player.lastname')]);

        return $dataProvider;
    }
}
