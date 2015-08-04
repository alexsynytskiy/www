<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MatchEvent;

/**
 * MatchEventSearch represents the model behind the search form about `common\models\MatchEvent`.
 */
class MatchEventSearch extends MatchEvent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'match_id', 'match_event_type_id', 'composition_id', 'minute', 'substitution_id', 'additional_minute', 'is_hidden', 'position'], 'integer'],
            [['notes', 'created_at', 'updated_at'], 'safe'],
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
        $query = MatchEvent::find();
        $match = new Match;
        $matchEventTable = MatchEvent::tableName();
        $matchTable = Match::tableName();

        $query->joinWith(['match' => function($query) use ($matchTable) {
            $query->from(['match' => $matchTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            "{$matchEventTable}.id" => $this->id,
            'match_id' => $this->match_id,
            'match_event_type_id' => $this->match_event_type_id,
            'composition_id' => $this->composition_id,
            'minute' => $this->minute,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'substitution_id' => $this->substitution_id,
            'additional_minute' => $this->additional_minute,
            'is_hidden' => $this->is_hidden,
            'position' => $this->position,
        ]);

        $query->andFilterWhere(['like', "notes", $this->notes]);

        return $dataProvider;
    }
}
