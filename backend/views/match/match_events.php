<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Match */
/* @var $homeCompositionDataProvider */
/* @var $guestCompositionDataProvider */

$this->title = 'События матча: '. $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Матчи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
?>
<div class="match-events-container">

    <?= $this->render('@backend/views/match-event/create', [
        'model' => $matchEventModel, 
    ]) ?>

    <!-- Compositions -->
    <div class="row">
        <div class="col-sm-3">
            <?php if(isset($model->command_home_id) && isset($model->season_id)) { ?>
                <?= $this->render('@backend/views/match/composition_simple_view', [
                    'dataProvider' => $homeCompositionDataProvider,
                ]) ?>
            <?php } ?>
        </div>
        
        <div class="col-sm-3">
            <?php if(isset($model->command_guest_id) && isset($model->season_id)) { ?>
                <?= $this->render('@backend/views/match/composition_simple_view', [
                    'dataProvider' => $guestCompositionDataProvider,
                ]) ?>
            <?php } ?>
        </div>

        <div class="col-sm-6">
            <?php if(isset($model->command_home_id) && isset($model->command_guest_id)) { ?>
                <?= $this->render('@backend/views/match/stat_update', [
                    'model' => $model,
                ]) ?>
            <?php } ?>
        </div>
    </div>

    <?= $this->render('@backend/views/match-event/index', [
        'dataProvider' => $matchEventDataProvider, 
        'searchModel' => $matchEventModelSearch, 
        'eventFilter' => $eventFilter,
    ]) ?>
    
</div>