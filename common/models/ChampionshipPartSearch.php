<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ChampionshipPart;

/**
 * ChampionshipPartSearch represents the model behind the search form about `common\models\ChampionshipPart`.
 */
class ChampionshipPartSearch extends ChampionshipPart
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'championship.name'], 'safe'],
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
            'championship.name',
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
        $query = ChampionshipPart::find();
        $championship = new Championship;
        $championshipPartTable = ChampionshipPart::tableName();
        $championshipTable = Championship::tableName();

        $query->joinWith(['championship' => function($query) use ($championshipTable) {
            $query->from(['championship' => $championshipTable]);
        }]);

         $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["championship.name"];
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
            "{$championshipPartTable}.id" => $this->id,
        ]);

        $query->andFilterWhere(['like', "{$championshipPartTable}.name", $this->name])
              ->andFilterWhere(['like', 'championship.name', $this->getAttribute('championship.name')]);

        return $dataProvider;
    }
}
