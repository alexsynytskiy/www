<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Membership;

/**
 * MembershipSearch represents the model behind the search form about `common\models\Membership`.
 */
class MembershipSearch extends Membership
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'command_id', 'player_id', 'number', 'amplua_id'], 'integer'],
            [['created_at', 'updated_at', 'team.name', 'player.name', 'amplua.name',], 'safe'],
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
            'team.name',
            'player.name',
            'amplua.name',
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
        $query = Membership::find();

        $membershipTable = Membership::tableName();
        $teamTable = Team::tableName();
        $playerTable = Player::tableName();
        $ampluaTable = Amplua::tableName();

        $query->joinWith(['team' => function($query) use ($teamTable) {
            $query->from(['team' => $teamTable]);
        }]);
        $query->joinWith(['amplua' => function($query) use ($ampluaTable) {
            $query->from(['amplua' => $ampluaTable]);
        }]);
        $query->joinWith(['player' => function($query) use ($playerTable) {
            $query->from(['player' => $playerTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        // enable sorting for the related columns
        $addSortAttributes = [
            "team.name",
            "amplua.name",
            "player.name",
        ];
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
            "{$membershipTable}.id" => $this->id,
            'command_id' => $this->command_id,
            'player_id' => $this->player_id,
            'number' => $this->number,
            'amplua_id' => $this->amplua_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'team.name', $this->getAttribute('team.name')]);
        $query->andFilterWhere(['like', 'player.lastname', $this->getAttribute('player.name')]);
        $query->andFilterWhere(['like', 'amplua.name', $this->getAttribute('amplua.name')]);

        return $dataProvider;
    }
}
