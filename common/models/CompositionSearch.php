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
            [['contract_type', 'team.name'], 'safe'],
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
            'player.lastname',
            'team.name',
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
        $query = Composition::find();

        $compositionTable = Composition::tableName();
        $teamTable = Team::tableName();

        $query->joinWith(['team' => function($query) use ($teamTable) {
            $query->from(['team' => $teamTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        // enable sorting for the related columns
        $addSortAttributes = [
            "team.name",
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
            "{$compositionTable}.id" => $this->id,
            'match_id' => $this->match_id,
            'contract_id' => $this->contract_id,
            'is_substitution' => $this->is_substitution,
            'is_basis' => $this->is_basis,
            'number' => $this->number,
            'is_captain' => $this->is_captain,
            'command_id' => $this->command_id,
        ]);

        $query->andFilterWhere(['like', 'contract_type', $this->contract_type])
            ->andFilterWhere(['like', 'team.name', $this->getAttribute('team.name')]);

        return $dataProvider;
    }
}
