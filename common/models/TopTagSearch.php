<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TopTag;

/**
 * TopTagSearch represents the model behind the search form about `common\models\TopTag`.
 */
class TopTagSearch extends TopTag
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tag_id'], 'integer'],
            [['tag.name'], 'safe'],
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
        return array_merge(parent::attributes(), ['tag.name']);
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
        $query = TopTag::find();

        $topTagTable = TopTag::tableName();
        $tagTable = Tag::tableName();

        $query->joinWith(['tag' => function($query) use ($tagTable) {
            $query->from(['tag' => $tagTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["tag.name"];
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
            'tag_id' => $this->tag_id,
        ]);
        $query->andFilterWhere(['like', 'tag.name', $this->getAttribute('tag.name')]);

        return $dataProvider;
    }
}
