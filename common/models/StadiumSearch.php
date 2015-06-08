<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Stadium;

/**
 * StadiumSearch represents the model behind the search form about `common\models\Stadium`.
 */
class StadiumSearch extends Stadium
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'spectators'], 'integer'],
            [['name', 'country.name'], 'safe'],
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
        $query = Stadium::find();
        $country = new Country;
        $stadiumTable = Stadium::tableName();
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
            "{$stadiumTable}.id" => $this->id,
            'spectators' => $this->spectators,
        ]);

        $query->andFilterWhere(['like', "{$stadiumTable}.name", $this->name])
              ->andFilterWhere(['like', 'country.name', $this->getAttribute('country.name')]);

        return $dataProvider;
    }
}
