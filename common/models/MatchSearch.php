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
            [['id', 'is_visible', 'home_shots', 'guest_shots', 'home_shots_in', 'guest_shots_in', 'home_offsides', 'guest_offsides', 'home_corners', 'guest_corners', 'home_fouls', 'guest_fouls', 'home_yellow_cards', 'guest_yellow_cards', 'home_red_cards', 'guest_red_cards', 'home_goals', 'guest_goals', 'comments_count', 'is_finished'], 'integer'],
            [['championship.name', 'commandGuest.name', 'commandHome.name', 'stadium.name', 'round', 'date', 'created_at', 'updated_at', 'announcement'], 'safe'],
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
            'commandGuest.name',
            'commandHome.name',
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
        $commandHome = new Command;
        $stadium = new Stadium;
        $matchTable = Match::tableName();
        $championshipTable = Championship::tableName();
        $commandTable = Command::tableName();
        $stadiumTable = Stadium::tableName();

        $query->joinWith(['championship' => function($query) use ($championshipTable) {
            $query->from(['championship' => $championshipTable]);
        }]);

        $query->joinWith(['commandHome' => function($query) use ($commandTable) {
            $query->from(['commandHome' => $commandTable]);
        }]);

        $query->joinWith(['commandGuest' => function($query) use ($commandTable) {
            $query->from(['commandGuest' => $commandTable]);
        }]);

        $query->joinWith(['stadium' => function($query) use ($stadiumTable) {
            $query->from(['stadium' => $stadiumTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["championship.name"];
        foreach ($addSortAttributes as $addSortAttribute) {
            $dataProvider->sort->attributes[$addSortAttribute] = [
                'asc'   => [$addSortAttribute => SORT_ASC],
                'desc'  => [$addSortAttribute => SORT_DESC],
            ];
        }

        // enable sorting for the related columns
        $addSortAttributes = ["commandHome.name"];
        foreach ($addSortAttributes as $addSortAttribute) {
            $dataProvider->sort->attributes[$addSortAttribute] = [
                'asc'   => [$addSortAttribute => SORT_ASC],
                'desc'  => [$addSortAttribute => SORT_DESC],
            ];
        }

        // enable sorting for the related columns
        $addSortAttributes = ["commandGuest.name"];
        foreach ($addSortAttributes as $addSortAttribute) {
            $dataProvider->sort->attributes[$addSortAttribute] = [
                'asc'   => [$addSortAttribute => SORT_ASC],
                'desc'  => [$addSortAttribute => SORT_DESC],
            ];
        }

        // enable sorting for the related columns
        $addSortAttributes = ["stadium.name"];
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
            'comments_count' => $this->comments_count,
            'is_finished' => $this->is_finished,
        ]);

        $createdTime = strtotime($this->created_at);
        $startDay = date("Y-m-d 00:00:00",$createdTime);
        $endDay = date("Y-m-d 00:00:00", $createdTime + 60*60*24);
        if($this->created_at) {
            $query->where(['between', 'created_at', $startDay, $endDay]);
        }
        
        $updatedTime = strtotime($this->updated_at);
        $startDay = date("Y-m-d 00:00:00",$updatedTime);
        $endDay = date("Y-m-d 00:00:00", $updatedTime + 60*60*24);
        if($this->updated_at) {
            $query->where(['between', 'updated_at', $startDay, $endDay]);
        }

        $date = strtotime($this->date);
        $startDay = date("Y-m-d 00:00:00",$date);
        $endDay = date("Y-m-d 00:00:00", $date + 60*60*24);
        if($this->date) {
            $query->where(['between', 'date', $startDay, $endDay]);
        }

        $query->andFilterWhere(['like', 'round', $this->round])
              ->andFilterWhere(['like', 'announcement', $this->announcement])
              ->andFilterWhere(['like', "commandHome.name", $this->getAttribute('commandHome.name')])
              ->andFilterWhere(['like', "commandGuest.name", $this->getAttribute('commandGuest.name')])
              ->andFilterWhere(['like', 'championship.name', $this->getAttribute('championship.name')])
              ->andFilterWhere(['like', 'stadium.name', $this->getAttribute('stadium.name')]);

        return $dataProvider;
    }
}
