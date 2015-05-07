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
            [['id', 'is_visible', 'championship_id', 'command_home_id', 'command_guest_id', 'stadium_id', 'season_id', 'arbiter_main_id', 'arbiter_assistant_1_id', 'arbiter_assistant_2_id', 'arbiter_reserve_id', 'home_shots', 'guest_shots', 'home_shots_in', 'guest_shots_in', 'home_offsides', 'guest_offsides', 'home_corners', 'guest_corners', 'home_fouls', 'guest_fouls', 'home_yellow_cards', 'guest_yellow_cards', 'home_red_cards', 'guest_red_cards', 'home_goals', 'guest_goals', 'comments_count', 'championship_part_id', 'league_id', 'is_finished'], 'integer'],
            [['round', 'date', 'created_at', 'updated_at', 'announcement'], 'safe'],
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
        $query = Match::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_visible' => $this->is_visible,
            'championship_id' => $this->championship_id,
            'command_home_id' => $this->command_home_id,
            'command_guest_id' => $this->command_guest_id,
            'stadium_id' => $this->stadium_id,
            'season_id' => $this->season_id,
            'date' => $this->date,
            'arbiter_main_id' => $this->arbiter_main_id,
            'arbiter_assistant_1_id' => $this->arbiter_assistant_1_id,
            'arbiter_assistant_2_id' => $this->arbiter_assistant_2_id,
            'arbiter_reserve_id' => $this->arbiter_reserve_id,
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'championship_part_id' => $this->championship_part_id,
            'league_id' => $this->league_id,
            'is_finished' => $this->is_finished,
        ]);

        $query->andFilterWhere(['like', 'round', $this->round])
            ->andFilterWhere(['like', 'announcement', $this->announcement]);

        return $dataProvider;
    }
}
