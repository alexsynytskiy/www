<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SelectedBlog;

/**
 * SelectedBlogSearch represents the model behind the search form about `common\models\SelectedBlog`.
 */
class SelectedBlogSearch extends SelectedBlog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'post_id'], 'integer'],
            [['post.title'], 'safe'],
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
        return array_merge(parent::attributes(), ['post.title']);
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
        $query = SelectedBlog::find();

        $selectedBlogTable = SelectedBlog::tableName();
        $blogTable = Post::tableName();

        $query->joinWith(['post' => function($query) use ($blogTable) {
            $query->from(['post' => $blogTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["post.title"];
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
            'id' => $this->id,
            'post_id' => $this->post_id,
        ]);

        $query->andFilterWhere(['like', 'post.title', $this->getAttribute('post.title')]);

        return $dataProvider;
    }
}
