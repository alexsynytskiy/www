<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Contract;

/**
 * ContractSearch represents the model behind the search form about `common\models\Contract`.
 */
class ContractSearch extends Contract
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'command_id', 'season_id', 'amplua_id', 'contractable_id', 'number', 'command_from_id', 'year_from', 'year_till', 'matches', 'goals', 'is_active'], 'integer'],
            [['contractable_type', 'debut', 'created_at', 'updated_at'], 'safe'],
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
        $query = Contract::find();

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
            'command_id' => $this->command_id,
            'season_id' => $this->season_id,
            'amplua_id' => $this->amplua_id,
            'contractable_id' => $this->contractable_id,
            'number' => $this->number,
            'command_from_id' => $this->command_from_id,
            'year_from' => $this->year_from,
            'year_till' => $this->year_till,
            'matches' => $this->matches,
            'goals' => $this->goals,
            'is_active' => $this->is_active,
            'debut' => $this->debut,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'contractable_type', $this->contractable_type]);

        return $dataProvider;
    }
}
