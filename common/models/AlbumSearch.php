<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Album;

/**
 * AlbumSearch represents the model behind the search form about `common\models\Album`.
 */
class AlbumSearch extends Album
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'is_public'], 'integer'],
            [['user.username', 'title', 'slug', 'description', 'created_at', 'updated_at', 'cached_tag_list'], 'safe'],
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
        $user = Yii::$app->getModule("user")->model("User");
        $query = Album::find();

        $postTable = Album::tableName();
        // set up query with relation to `user.username`
        $userTable = $user::tableName();
        $query->joinWith(['user' => function($query) use ($userTable) {
            $query->from(['user' => $userTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
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
            "{$postTable}.id" => $this->id,
            'user_id' => $this->user_id,
            'is_public' => $this->is_public,
        ]);

        if($this->created_at) {
            $createdTime = strtotime($this->created_at);
            $startDay = date("Y-m-d 00:00:00",$createdTime);
            $endDay = date("Y-m-d 00:00:00", $createdTime + 60*60*24);
            $query->andFilterWhere(['between', 'created_at', $startDay, $endDay]);
        }

        if($this->updated_at) {
            $updatedTime = strtotime($this->updated_at);
            $startDay = date("Y-m-d 00:00:00",$updatedTime);
            $endDay = date("Y-m-d 00:00:00", $updatedTime + 60*60*24);
            $query->andFilterWhere(['between', 'updated_at', $startDay, $endDay]);
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'cached_tag_list', $this->cached_tag_list])
            ->andFilterWhere(['like', 'user.username', $this->getAttribute('user.username')]);

        return $dataProvider;
    }
}
