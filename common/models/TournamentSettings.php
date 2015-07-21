<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tournament_settings".
 *
 * @property integer $id
 * @property integer $season_id
 * @property integer $scored_missed_weight
 * @property integer $goal_scored_weight
 * @property integer $goal_missed_weight
 * @property integer $win_weight
 * @property integer $draw_weight
 * @property integer $defeat_weight
 * @property string $cl_positions
 * @property string $el_positions
 * @property string $reduction_positions
 */
class TournamentSettings extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tournament_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['season_id', 'scored_missed_weight', 'goal_scored_weight', 'goal_missed_weight', 'win_weight', 'draw_weight', 'defeat_weight', 'cl_positions', 'el_positions', 'reduction_positions'], 'required'],
            [['season_id', 'scored_missed_weight', 'goal_scored_weight', 'goal_missed_weight', 'win_weight', 'draw_weight', 'defeat_weight'], 'integer'],
            [['cl_positions', 'el_positions', 'reduction_positions'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'season_id' => 'Сезон',
            'scored_missed_weight' => 'Вес разницы забитых-пропущенных',
            'goal_scored_weight' => 'Вес забитых',
            'goal_missed_weight' => 'Вес пропущенных',
            'win_weight' => 'Вес побед',
            'draw_weight' => 'Вес ничьих',
            'defeat_weight' => 'Вес поражений',
            'cl_positions' => 'Позиции в ЛЧ',
            'el_positions' => 'Позиции в ЛЕ',
            'reduction_positions' => 'Позиции на вылет',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeason()
    {
        return $this->hasOne(Season::className(), ['id' => 'season_id']);
    }
}
