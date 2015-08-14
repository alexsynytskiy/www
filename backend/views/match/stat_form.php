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
            if($model->home_shots == null) {
                $model->home_shots = 0;
            }
            echo $form->field($model, 'home_shots')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_shots == null) {
                $model->guest_shots = 0;
            }
            echo $form->field($model, 'guest_shots')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_shots_in == null) {
                $model->home_shots_in = 0;
            }
            echo $form->field($model, 'home_shots_in')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_shots_in == null) {
                $model->guest_shots_in = 0;
            }
            echo $form->field($model, 'guest_shots_in')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_offsides == null) {
                $model->home_offsides = 0;
            }
            echo $form->field($model, 'home_offsides')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_offsides == null) {
                $model->guest_offsides = 0;
            }
            echo $form->field($model, 'guest_offsides')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_corners == null) {
                $model->home_corners = 0;
            }
            echo $form->field($model, 'home_corners')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_corners == null) {
                $model->guest_corners = 0;
            }
            echo $form->field($model, 'guest_corners')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_fouls == null) {
                $model->home_fouls = 0;
            }
            echo $form->field($model, 'home_fouls')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_fouls == null) {
                $model->guest_fouls = 0;
            }
            echo $form->field($model, 'guest_fouls')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_yellow_cards == null) {
                $model->home_yellow_cards = 0;
            }
            echo $form->field($model, 'home_yellow_cards')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_yellow_cards == null) {
                $model->guest_yellow_cards = 0;
            }
            echo $form->field($model, 'guest_yellow_cards')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_red_cards == null) {
                $model->home_red_cards = 0;
            }
            echo $form->field($model, 'home_red_cards')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_red_cards == null) {
                $model->guest_red_cards = 0;
            }
            echo $form->field($model, 'guest_red_cards')->textInput();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_goals == null) {
                $model->home_goals = 0;
            }
            echo $form->field($model, 'home_goals')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_goals == null) {
                $model->guest_goals = 0;
            }
            echo $form->field($model, 'guest_goals')->textInput();
        ?>    
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_ball_possession == null) {
                $model->home_ball_possession = 0;
            }
            echo $form->field($model, 'home_ball_possession')->textInput();
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_ball_possession == null) {
                $model->guest_ball_possession = 0;
            }
            echo $form->field($model, 'guest_ball_possession')->textInput();
        ?>    
    </div>
</div>