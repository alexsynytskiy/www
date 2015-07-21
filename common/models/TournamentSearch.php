<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tournament;

/**
 * TournamentSearch represents the model behind the search form about `common\models\Tournament`.
 */
class TournamentSearch extends Tournament
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'command_id', 'championship_id', 'season_id', 'played', 'won', 'draw', 'lost', 'goals_for', 'goals_against', 'points', 'league_id'], 'integer'],
            [['created_at', 'updated_at', 'team.name', 'league.name', 'season.name', 'championship.name'], 'safe'],
            [['fair_play'], 'number'],
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
            'league.name',
            'season.name',
            'championship.name',
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
        $query = Tournament::find();

        $tournamentTable = Tournament::tableName();
        $teamTable = Team::tableName();
        $leagueTable = League::tableName();
        $championshipTable = Championship::tableName();
        $seasonTable = Season::tableName();

        $query->joinWith(['team' => function($query) use ($teamTable) {
            $query->from(['team' => $teamTable]);
        }]);
        $query->joinWith(['league' => function($query) use ($leagueTable) {
            $query->from(['league' => $leagueTable]);
        }]);
        $query->joinWith(['championship' => function($query) use ($championshipTable) {
            $query->from(['championship' => $championshipTable]);
        }]);
        $query->joinWith(['season' => function($query) use ($seasonTable) {
            $query->from(['season' => $seasonTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => ['defaultOrder' => ['points' => SORT_DESC]]
        ]);

        // enable sorting for the related columns
        $addSortAttributes = [
            "team.name",
            "league.name",
            "championship.name",
            "season.name",
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
            "{$tournamentTable}id" => $this->id,
            'command_id' => $this->command_id,
            'championship_id' => $this->championship_id,
            'season_id' => $this->season_id,
            'played' => $this->played,
            'won' => $this->won,
            'draw' => $this->draw,
            'lost' => $this->lost,
            'goals_for' => $this->goals_for,
            'goals_against' => $this->goals_against,
            'points' => $this->points,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'fair_play' => $this->fair_play,
            'league_id' => $this->league_id,
        ]);
        $query->andFilterWhere(['like', 'team.name', $this->getAttribute('team.name')]);

        return $dataProvider;
    }
}
