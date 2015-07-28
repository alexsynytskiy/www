<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Career;

/**
 * CareerSearch represents the model behind the search form about `common\models\Career`.
 */
class CareerSearch extends Career
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'player_id', 'league_id', 'season_id', 'command_id', 'championship_matches', 'championship_goals', 'cup_matches', 'cup_goals', 'euro_matches', 'euro_goals', 'goal_passes'], 'integer'],
            [['avg_mark'], 'number'],
            [['created_at', 
              'team.name',
              'player.lastname',
              'league.name',
              'season.name',
              'updated_at'], 'safe'],
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
            'league.name',
            'season.name',
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
        $query = Career::find();
        $team = new Team;
        $league = new League;
        $season = new Season;
        $player = new Player;
        $careerTable = Career::tableName();
        $teamTable = Team::tableName();
        $leagueTable = League::tableName();
        $seasonTable = Season::tableName();
        $playerTable = Player::tableName();

        $query->joinWith(['team' => function($query) use ($teamTable) {
            $query->from(['team' => $teamTable]);
        }]);

        $query->joinWith(['league' => function($query) use ($leagueTable) {
            $query->from(['league' => $leagueTable]);
        }]);

        $query->joinWith(['player' => function($query) use ($playerTable) {
            $query->from(['player' => $playerTable]);
        }]);

        $query->joinWith(['season' => function($query) use ($seasonTable) {
            $query->from(['season' => $seasonTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        $addSortAttributes = [
            'team.name',
            'player.lastname',
            'league.name',
            'season.name',
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
            "{$careerTable}.id" => $this->id,
            'player_id' => $this->player_id,
            'season_id' => $this->season_id,           
            'championship_matches' => $this->championship_matches,
            'championship_goals' => $this->championship_goals,
            'cup_matches' => $this->cup_matches,
            'cup_goals' => $this->cup_goals,
            'euro_matches' => $this->euro_matches,
            'euro_goals' => $this->euro_goals,
            'avg_mark' => $this->avg_mark,
            'goal_passes' => $this->goal_passes,
        ]);

        $query->andFilterWhere(['like', "team.name", $this->getAttribute('team.name')])
              ->andFilterWhere(['like', "player.lastname", $this->getAttribute('player.lastname')])
              ->andFilterWhere(['like', "league.name", $this->getAttribute('league.name')])
              ->andFilterWhere(['like', "season.name", $this->getAttribute('season.name')]);

        return $dataProvider;
    }
}
