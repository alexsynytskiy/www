<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Vote;

/**
 * VoteSearch represents the model behind the search form about `common\models\Vote`.
 */
class VoteSearch extends Vote
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vote', 'voteable_id', 'user_id', 'ip_address'], 'integer'],
            [['user.username', 'created_at', 'voteable_type'], 'safe'],
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
        return array_merge(parent::attributes(), ['user.username']);
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
        $query = Vote::find();
        $voteTable = Vote::tableName();

        // set up query with relation to `user.username`
        $user = Yii::$app->getModule("user")->model("User");
        $userTable = $user::tableName();

        $query->joinWith(['user' => function($query) use ($userTable) {
            $query->from(['user' => $userTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["user.username"];
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
            "{$voteTable}.id" => $this->id,
            'vote' => $this->vote,
            'voteable_id' => $this->voteable_id,
            'user_id' => $this->user_id,
            'ip_address' => $this->ip_address,
        ]);

        $createdTime = strtotime($this->created_at);
        $startDay = date("Y-m-d 00:00:00",$createdTime);
        $endDay = date("Y-m-d 00:00:00", $createdTime + 60*60*24);
        if($this->created_at) {
            $query->andFilterWhere(['between', 'created_at', $startDay, $endDay]);
        }

        $query->andFilterWhere(['like', 'voteable_type', $this->voteable_type])
            ->andFilterWhere(['like', 'user.username', $this->getAttribute('user.username')]);

        return $dataProvider;
    }
}
