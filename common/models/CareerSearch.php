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
            [['created_at', 'updated_at'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Career::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'player_id' => $this->player_id,
            'league_id' => $this->league_id,
            'season_id' => $this->season_id,
            'command_id' => $this->command_id,
            'championship_matches' => $this->championship_matches,
            'championship_goals' => $this->championship_goals,
            'cup_matches' => $this->cup_matches,
            'cup_goals' => $this->cup_goals,
            'euro_matches' => $this->euro_matches,
            'euro_goals' => $this->euro_goals,
            'avg_mark' => $this->avg_mark,
            'goal_passes' => $this->goal_passes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
