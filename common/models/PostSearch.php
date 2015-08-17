<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post;

/**
 * PostSearch represents the model behind the search form about `common\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'is_public', 'is_index', 'is_top', 'is_pin',
                'with_video', 'with_photo', 'content_category_id',
                'is_yandex_rss', 'allow_comment'], 'integer'],
            [['user.username', 'tag.name', 'title', 'slug',
                'content', 'created_at', 'updated_at', 'source_title',
                'source_url', 'cached_tag_list'], 'safe'],
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
            'user.username',
            'tag.name',
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
        $user = Yii::$app->getModule("user")->model("User");
        $query = Post::find();

        $postTable = Post::tableName();
        // set up query with relation to `user.username`
        $userTable = $user::tableName();

        $query->joinWith(['user' => function($query) use ($userTable) {
            $query->from(['user' => $userTable]);
        }]);

        $tagTable = Tag::tableName();
        $taggingTable = Tagging::tableName();
        $query->leftJoin($taggingTable, "$taggingTable.taggable_id = $postTable.id AND $taggingTable.taggable_type = '".Tagging::TAGGABLE_POST."'");
        $query->leftJoin($tagTable, "$tagTable.id = $taggingTable.tag_id");

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
            'is_index' => $this->is_index,
            'is_top' => $this->is_top,
            'is_pin' => $this->is_pin,
            'with_video' => $this->with_video,
            'with_photo' => $this->with_photo,
            'content_category_id' => $this->content_category_id,
            'is_yandex_rss' => $this->is_yandex_rss,
            'allow_comment' => $this->allow_comment,
        ]);

        $createdTime = strtotime($this->created_at);
        $startDay = date("Y-m-d 00:00:00",$createdTime);
        $endDay = date("Y-m-d 00:00:00", $createdTime + 60*60*24);
        if($this->created_at) {
            $query->andFilterWhere(['between', 'created_at', $startDay, $endDay]);
        }
        
        $updatedTime = strtotime($this->updated_at);
        $startDay = date("Y-m-d 00:00:00",$updatedTime);
        $endDay = date("Y-m-d 00:00:00", $updatedTime + 60*60*24);
        if($this->updated_at) {
            $query->andFilterWhere(['between', 'updated_at', $startDay, $endDay]);
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'source_title', $this->source_title])
            ->andFilterWhere(['like', 'source_url', $this->source_url])
            ->andFilterWhere(['like', 'cached_tag_list', $this->cached_tag_list])
            ->andFilterWhere(['like', 'user.username', $this->getAttribute('user.username')])
            ->andFilterWhere(['like', "$tagTable.name", $this->getAttribute('tag.name')]);

        return $dataProvider;
    }
}
