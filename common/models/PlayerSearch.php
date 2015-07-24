<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Player;

/**
 * PlayerSearch represents the model behind the search form about `common\models\Player`.
 */
class PlayerSearch extends Player
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'height', 'weight', 'amplua_id', 'country_id'], 'integer'],
            [['amplua.name' ,'firstname', 'lastname', 'birthday', 'slug', 'notes', 'created_at', 'updated_at', 'image', 'more_ampluas'], 'safe'],
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
        return array_merge(parent::attributes(), ['amplua.name']);
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
        $query = Player::find();

        $playerTable = Player::tableName();
        // set up query with relation to `user.username`
        $ampluaTable = Amplua::tableName();

        $query->joinWith(['amplua' => function($query) use ($ampluaTable) {
            $query->from(['amplua' => $ampluaTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["amplua.name"];
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
            "{$playerTable}.id" => $this->id,
            'birthday' => $this->birthday,
            'height' => $this->height,
            'weight' => $this->weight,
            'amplua_id' => $this->amplua_id,
            'country_id' => $this->country_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'more_ampluas', $this->more_ampluas])
            ->andFilterWhere(['like', 'amplua.name', $this->getAttribute('amplua.name')]);

        return $dataProvider;
    }
}
