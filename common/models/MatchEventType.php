<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "match_event_types".
 *
 * @property integer $id
 * @property string $name
 */
class MatchEventType extends ActiveRecord
{
    /**
     * @var File Team icon
     */
    public $icon;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'match_event_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],

            //required
            [['name'], 'required'],

             // image
            [['icon'], 'file', 'extensions' => 'jpeg, jpg , gif, png, svg'],
        ];
    }

    /**
     * @return Asset
     */
    public function getAsset()
    {
        $asset = Asset::getAssets($this->id, Asset::ASSETABLE_MATCH_EVENT, NULL, true);
        if($asset->assetable_type == null) {
            $asset->assetable_type = Asset::ASSETABLE_MATCH_EVENT;
        }
        return $asset;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'icon' => 'Иконка события',
        ];
    }
}
