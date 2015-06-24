<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Claim;

/**
 * ClaimSearch represents the model behind the search form about `common\models\Claim`.
 */
class ClaimSearch extends Claim
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'comment_id', 'user_id', 'comment_author'], 'integer'],
            [['message', 'created_at', 'updated_at', 'user.username', 'commentAuthor.username'], 'safe'],
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
        return array_merge(parent::attributes(), ['user.username', 'commentAuthor.username']);
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
        $query = Claim::find();
        $user = Yii::$app->getModule("user")->model("User");

        $claimTable = Claim::tableName();
        $userTable = $user::tableName();

        $query->joinWith(['user' => function($query) use ($userTable) {
            $query->from(['user' => $userTable]);
        }]);
        $query->joinWith(['commentAuthor' => function($query) use ($userTable) {
            $query->from(['commentAuthor' => $userTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ['user.username', 'commentAuthor.username'];
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
            "{$claimTable}.id" => $this->id,
            'comment_id' => $this->comment_id,
            'user_id' => $this->user_id,
            'comment_author' => $this->comment_author,
        ]);

        $createdTime = strtotime($this->created_at);
        $startDay = date("Y-m-d 00:00:00",$createdTime);
        $endDay = date("Y-m-d 00:00:00", $createdTime + 60*60*24);
        if($this->created_at) {
            $query->andFilterWhere(['between', 'created_at', $startDay, $endDay]);
        }

        $query->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'user.username', $this->getAttribute('user.username')])
            ->andFilterWhere(['like', 'commentAuthor.username', $this->getAttribute('commentAuthor.username')]);

        return $dataProvider;
    }
}
