<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Match */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="row">
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'home_shots')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'guest_shots')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'home_shots_in')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'guest_shots_in')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'home_offsides')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'guest_offsides')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'home_corners')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'guest_corners')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'home_fouls')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'guest_fouls')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'home_yellow_cards')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'guest_yellow_cards')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'home_red_cards')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'guest_red_cards')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'home_goals')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'guest_goals')->textInput();
        ?>    
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'home_ball_possession')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            echo $form->field($model, 'guest_ball_possession')->textInput();
        ?>    
    </div>
</div>