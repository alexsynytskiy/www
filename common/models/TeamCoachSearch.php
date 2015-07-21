<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TeamCoach;

/**
 * TeamCoachSearch represents the model behind the search form about `common\models\TeamCoach`.
 */
class TeamCoachSearch extends TeamCoach
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'season_id', 'coach_id', 'is_main'], 'integer'],
            [['team.name', 'coach.name', 'season.name'], 'safe'],
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
            'coach.name',
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
        $query = TeamCoach::find();

        $teamCoachTable = TeamCoach::tableName();
        $coachTable = Coach::tableName();
        $teamTable = Team::tableName();
        $seasonTable = Season::tableName();

        $query->joinWith(['coach' => function($query) use ($coachTable) {
            $query->from(['coach' => $coachTable]);
        }]);

        $query->joinWith(['team' => function($query) use ($teamTable) {
            $query->from(['team' => $teamTable]);
        }]);

        $query->joinWith(['season' => function($query) use ($seasonTable) {
            $query->from(['season' => $seasonTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $addSortAttributes = [
            "coach.name",
            "team.name",
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
            "{$teamCoachTable}.id" => $this->id,
            'team_id' => $this->team_id,
            'season_id' => $this->season_id,
            'coach_id' => $this->coach_id,
            'is_main' => $this->is_main,
        ]);

        $query->andFilterWhere(['like', 'coach.name', $this->getAttribute('coach.name')])
            ->andFilterWhere(['like', 'team.name', $this->getAttribute('team.name')])
            ->andFilterWhere(['like', 'season.name', $this->getAttribute('season.name')]);

        return $dataProvider;
    }
}
