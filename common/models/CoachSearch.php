<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Coach;

/**
 * CoachSearch represents the model behind the search form about `common\models\Coach`.
 */
class CoachSearch extends Coach
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'country.name', 'birthday', 'slug', 'position', 'notes', 'player_carrer', 'coach_carrer', 'created_at', 'updated_at', 'image'], 'safe'],
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
            'country.name',
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
        $query = Coach::find();
        $country = new Country;
        $coachTable = Coach::tableName();
        $countryTable = Country::tableName();

        $query->joinWith(['country' => function($query) use ($countryTable) {
            $query->from(['country' => $countryTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["country.name"];
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
            "{$coachTable}.id" => $this->id,
            'birthday' => $this->birthday,
            'country_id' => $this->country_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', "{$coachTable}.name", $this->name])
              ->andFilterWhere(['like', 'slug', $this->slug])
              ->andFilterWhere(['like', 'position', $this->position])
              ->andFilterWhere(['like', 'notes', $this->notes])
              ->andFilterWhere(['like', 'player_carrer', $this->player_carrer])
              ->andFilterWhere(['like', 'coach_carrer', $this->coach_carrer])
              ->andFilterWhere(['like', 'image', $this->image])
              ->andFilterWhere(['like', 'country.name', $this->getAttribute('country.name')]);

        return $dataProvider;
    }
}
