<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TournamentSettings;

/**
 * TournamentSettingsSearch represents the model behind the search form about `common\models\TournamentSettings`.
 */
class TournamentSettingsSearch extends TournamentSettings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'season_id', 'scored_missed_weight', 'goal_scored_weight', 'goal_missed_weight', 'win_weight', 'draw_weight', 'defeat_weight'], 'integer'],
            [['cl_positions', 'el_positions', 'reduction_positions'], 'safe'],
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
        $query = TournamentSettings::find();

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
            'season_id' => $this->season_id,
            'scored_missed_weight' => $this->scored_missed_weight,
            'goal_scored_weight' => $this->goal_scored_weight,
            'goal_missed_weight' => $this->goal_missed_weight,
            'win_weight' => $this->win_weight,
            'draw_weight' => $this->draw_weight,
            'defeat_weight' => $this->defeat_weight,
        ]);

        $query->andFilterWhere(['like', 'cl_positions', $this->cl_positions])
            ->andFilterWhere(['like', 'el_positions', $this->el_positions])
            ->andFilterWhere(['like', 'reduction_positions', $this->reduction_positions]);

        return $dataProvider;
    }
}
