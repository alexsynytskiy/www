<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Match;

/**
 * MatchSearch represents the model behind the search form about `common\models\Match`.
 */
class MatchSearch extends Match
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_visible', 'home_shots', 'guest_shots',
              'home_shots_in', 'guest_shots_in', 'home_offsides', 
              'guest_offsides', 'home_corners', 'guest_corners', 
              'home_fouls', 'guest_fouls', 'home_yellow_cards', 
              'guest_yellow_cards', 'home_red_cards', 'guest_red_cards', 
              'home_goals', 'guest_goals', 'is_finished',
              'championship_id', 'season_id', 'league_id',
              'command_home_id', 'command_guest_id'], 'integer'],
            [['championship.name', 
              'teamGuest.name', 
              'teamHome.name',
              'arbiterMain.name',
              'arbiterAssistant1.name',
              'arbiterAssistant2.name',
              'arbiterAssistant3.name',
              'arbiterAssistant4.name',
              'arbiterReserve.name', 
              'stadium.name',
              'championshipPart.name',
              'round', 
              'date', 
              'created_at', 
              'updated_at', 
              'announcement'], 'safe'],
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
            'championship.name',
            'teamGuest.name',
            'teamHome.name',
            'arbiterMain.name',
            'arbiterAssistant1.name',
            'arbiterAssistant2.name',
            'arbiterAssistant3.name',
            'arbiterAssistant4.name',
            'arbiterReserve.name',
            'championshipPart.name',
            'stadium.name',
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
        $query = Match::find();
        $championship = new Championship;
        $teamHome = new Team;
        $stadium = new Stadium;
        $arbiter = new Arbiter;
        $championshipPart = new ChampionshipPart;
        $matchTable = Match::tableName();
        $championshipTable = Championship::tableName();
        $championshipPartTable = ChampionshipPart::tableName();
        $teamTable = Team::tableName();
        $stadiumTable = Stadium::tableName();
        $arbiterTable = Arbiter::tableName();

        $query->joinWith(['championship' => function($query) use ($championshipTable) {
            $query->from(['championship' => $championshipTable]);
        }]);

        $query->joinWith(['teamHome' => function($query) use ($teamTable) {
            $query->from(['teamHome' => $teamTable]);
        }]);

        $query->joinWith(['teamGuest' => function($query) use ($teamTable) {
            $query->from(['teamGuest' => $teamTable]);
        }]);

        $query->joinWith(['arbiterMain' => function($query) use ($arbiterTable) {
            $query->from(['arbiterMain' => $arbiterTable]);
        }]);

        $query->joinWith(['arbiterAssistant1' => function($query) use ($arbiterTable) {
            $query->from(['arbiterAssistant1' => $arbiterTable]);
        }]);

        $query->joinWith(['arbiterAssistant2' => function($query) use ($arbiterTable) {
            $query->from(['arbiterAssistant2' => $arbiterTable]);
        }]);

        $query->joinWith(['arbiterAssistant3' => function($query) use ($arbiterTable) {
            $query->from(['arbiterAssistant3' => $arbiterTable]);
        }]);

        $query->joinWith(['arbiterAssistant4' => function($query) use ($arbiterTable) {
            $query->from(['arbiterAssistant4' => $arbiterTable]);
        }]);

        $query->joinWith(['arbiterReserve' => function($query) use ($arbiterTable) {
            $query->from(['arbiterReserve' => $arbiterTable]);
        }]);

        $query->joinWith(['championshipPart' => function($query) use ($championshipPartTable) {
            $query->from(['championshipPart' => $championshipPartTable]);
        }]);

        $query->joinWith(['stadium' => function($query) use ($stadiumTable) {
            $query->from(['stadium' => $stadiumTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]]
        ]);

        // enable sorting for the related columns
        $addSortAttributes = [
            "championship.name",
            "teamHome.name",
            "teamGuest.name",
            "arbiterMain.name",
            "arbiterAssistant1.name",
            "arbiterAssistant2.name",
            "arbiterAssistant3.name",
            "arbiterAssistant4.name",
            "arbiterReserve.name",
            "championshipPart.name",
            "stadium.name",
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
            "{$matchTable}.id" => $this->id,
            'is_visible' => $this->is_visible,            
            'home_shots' => $this->home_shots,
            'guest_shots' => $this->guest_shots,
            'home_shots_in' => $this->home_shots_in,
            'guest_shots_in' => $this->guest_shots_in,
            'home_offsides' => $this->home_offsides,
            'guest_offsides' => $this->guest_offsides,
            'home_corners' => $this->home_corners,
            'guest_corners' => $this->guest_corners,
            'home_fouls' => $this->home_fouls,
            'guest_fouls' => $this->guest_fouls,
            'home_yellow_cards' => $this->home_yellow_cards,
            'guest_yellow_cards' => $this->guest_yellow_cards,
            'home_red_cards' => $this->home_red_cards,
            'guest_red_cards' => $this->guest_red_cards,
            'home_goals' => $this->home_goals,
            'guest_goals' => $this->guest_goals,
            'is_finished' => $this->is_finished,
            "{$matchTable}.championship_id" => $this->championship_id,
            "{$matchTable}.season_id" => $this->season_id,
            "{$matchTable}.league_id" => $this->league_id,
            "{$matchTable}.command_home_id" => $this->command_home_id,
            "{$matchTable}.command_guest_id" => $this->command_guest_id,
        ]);

        $createdTime = strtotime($this->created_at);
        $startDay = date("Y-m-d 00:00:00",$createdTime);
        $endDay = date("Y-m-d 00:00:00", $createdTime + 60*60*24);
        if($this->created_at) {
            $query->andFilterWhere(['between', 'created_at', $startDay, $endDay]);
        }
        
        $updatedTime = strtotime($this->updated_at);
        $startDay = date("Y-m-d 00:00:00",$updatedTime);
        $endDay = date("Y-m-d 00:00:00", $updatedTime + 60*60*24);
        if($this->updated_at) {
            $query->andFilterWhere(['between', 'updated_at', $startDay, $endDay]);
        }

        $date = strtotime($this->date);
        $startDay = date("Y-m-d 00:00:00",$date);
        $endDay = date("Y-m-d 00:00:00", $date + 60*60*24);
        if($this->date) {
            $query->andFilterWhere(['between', 'date', $startDay, $endDay]);
        }

        $query->andFilterWhere(['like', 'round', $this->round])
              ->andFilterWhere(['like', 'announcement', $this->announcement])
              ->andFilterWhere(['like', "teamHome.name", $this->getAttribute('teamHome.name')])
              ->andFilterWhere(['like', "teamGuest.name", $this->getAttribute('teamGuest.name')])
              ->andFilterWhere(['like', 'championship.name', $this->getAttribute('championship.name')])
              ->andFilterWhere(['like', 'arbiterMain.name', $this->getAttribute('arbiterMain.name')])
              ->andFilterWhere(['like', 'arbiterAssistant1.name', $this->getAttribute('arbiterAssistant1.name')])
              ->andFilterWhere(['like', 'arbiterAssistant2.name', $this->getAttribute('arbiterAssistant2.name')])
              ->andFilterWhere(['like', 'arbiterAssistant3.name', $this->getAttribute('arbiterAssistant3.name')])
              ->andFilterWhere(['like', 'arbiterAssistant4.name', $this->getAttribute('arbiterAssistant4.name')])
              ->andFilterWhere(['like', 'arbiterReserve.name', $this->getAttribute('arbiterReserve.name')])
              ->andFilterWhere(['like', 'championshipPart.name', $this->getAttribute('championshipPart.name')])
              ->andFilterWhere(['like', 'stadium.name', $this->getAttribute('stadium.name')]);

        return $dataProvider;
    }
}
