<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Comment;

/**
 * CommentSearch represents the model behind the search form about `common\models\Comment`.
 */
class CommentSearch extends Comment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'commentable_id', 'user_id', 'parent_id'], 'integer'],
            [['content', 'created_at', 'commentable_type', 'user.username', 'profile.full_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['user.username', 'profile.full_name']);
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
        $query = Comment::find();
        $commentTable = Comment::tableName();
        $user = Yii::$app->getModule("user")->model("User");

        // set up query with relation to `user.username`
        $userTable = $user::tableName();
        $query->joinWith(['user' => function($query) use ($userTable) {
            $query->from(['user' => $userTable]);
        }]);
        $profileTable = \common\modules\user\models\Profile::tableName();
        $query->joinWith(['profile' => function($query) use ($profileTable) {
            $query->from(['profile' => $profileTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["user.username", 'profile.full_name'];
        foreach ($addSortAttributes as $addSortAttribute) {
            $dataProvider->sort->attributes[$addSortAttribute] = [
                'asc'   => [$addSortAttribute => SORT_ASC],
                'desc'  => [$addSortAttribute => SORT_DESC],
            ];
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $createdTime = strtotime($this->created_at);
        $startDay = date("Y-m-d 00:00:00", $createdTime);
        $endDay = date("Y-m-d 00:00:00", $createdTime + 60*60*24);
        if($this->created_at) {
            $query->andFilterWhere(['between', 'created_at', $startDay, $endDay]);
        }

        $query->andFilterWhere([
            "{$commentTable}.id" => $this->id,
            "{$commentTable}.user_id" => $this->user_id,
            'parent_id' => $this->parent_id,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'user.username', $this->getAttribute('user.username')])
            ->andFilterWhere(['like', 'profile.full_name', $this->getAttribute('profile.full_name')]);

        return $dataProvider;
    }
}
