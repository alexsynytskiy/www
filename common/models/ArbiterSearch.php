<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Arbiter;

/**
 * ArbiterSearch represents the model behind the search form about `common\models\Arbiter`.
 */
class ArbiterSearch extends Arbiter
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
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
        $query = Arbiter::find();
        $country = new Country;
        $arbiterTable = Arbiter::tableName();
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
            "{$arbiterTable}.id" => $this->id,
        ]);

        $query->andFilterWhere(['like', "{$arbiterTable}.name", $this->name])
              ->andFilterWhere(['like', 'country.name', $this->getAttribute('country.name')]);

        return $dataProvider;
    }
}
