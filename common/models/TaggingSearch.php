<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tagging;

/**
 * TaggingSearch represents the model behind the search form about `common\models\Tagging`.
 */
class TaggingSearch extends Tagging
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tag_id', 'taggable_id'], 'integer'],
            [['taggable_type'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Tagging::find();

        // set up query with relation to `tag.name`
        $tagTable = Tag::tableName();
        $query->joinWith(['tag' => function($query) use ($tagTable) {
            $query->from(['tag' => $tagTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["tag.name"];
        foreach ($addSortAttributes as $addSortAttribute) {
            $dataProvider->sort->attributes[$addSortAttribute] = [
                'asc'   => [$addSortAttribute => SORT_ASC],
                'desc'  => [$addSortAttribute => SORT_DESC],
            ];
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tag_id' => $this->tag_id,
            'taggable_id' => $this->taggable_id,
        ]);

        $query->andFilterWhere(['like', 'taggable_type', $this->taggable_type]);

        return $dataProvider;
    }
}
