<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Composition;

/**
 * CompositionSearch represents the model behind the search form about `common\models\Composition`.
 */
class CompositionSearch extends Composition
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'match_id', 'contract_id', 'is_substitution', 'is_basis', 'number', 'is_captain', 'command_id'], 'integer'],
            [['contract_type'], 'safe'],
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
        $query = Composition::find();

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
            'match_id' => $this->match_id,
            'contract_id' => $this->contract_id,
            'is_substitution' => $this->is_substitution,
            'is_basis' => $this->is_basis,
            'number' => $this->number,
            'is_captain' => $this->is_captain,
            'command_id' => $this->command_id,
        ]);

        $query->andFilterWhere(['like', 'contract_type', $this->contract_type]);

        return $dataProvider;
    }
}
