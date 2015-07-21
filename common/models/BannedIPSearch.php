<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BannedIP;

/**
 * BannedIPSearch represents the model behind the search form about `common\models\BannedIP`.
 */
class BannedIPSearch extends BannedIP
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'start_ip_num', 'end_ip_num'], 'integer'],
            [['title', 'start_ip_num_value', 'end_ip_num_value', 'created_at', 'updated_at'], 'safe'],
            [['ip_address'], 'string', 'max' => 50],
            [['ip_address'], 'match', 'pattern' => '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/i'],

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
        $query = BannedIP::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if(isset($this->ip_address) && trim($this->ip_address) != '') {
            $ipValue = ip2long($this->ip_address);
            $query->andFilterWhere(['or',
                ['start_ip_num' => $ipValue],
                ['and', 
                    ['<=', 'start_ip_num', $ipValue],
                    ['>=', 'end_ip_num', $ipValue],
                ],
            ]);
        }

        $dataProvider->sort->attributes['ip_address'] = [
                'asc'   => ['start_ip_num' => SORT_ASC],
                'desc'  => ['start_ip_num' => SORT_DESC],
            ];

        $query->andFilterWhere([
            'id' => $this->id,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'start_ip_num_value', $this->start_ip_num_value])
            ->andFilterWhere(['like', 'end_ip_num_value', $this->end_ip_num_value]);

        return $dataProvider;
    }
}
