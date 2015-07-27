<?php
use yii\helpers\Html;
use kartik\slider\Slider;

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
        
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">40</b>';
            echo $form->field($model, 'home_shots')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 40,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_shots == null) {
                $model->guest_shots = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">40</b>';
            echo $form->field($model, 'guest_shots')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 40,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_shots_in == null) {
                $model->home_shots_in = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">40</b>';
            echo $form->field($model, 'home_shots_in')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 40,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_shots_in == null) {
                $model->guest_shots_in = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">40</b>';
            echo $form->field($model, 'guest_shots_in')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 40,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_offsides == null) {
                $model->home_offsides = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">20</b>';
            echo $form->field($model, 'home_offsides')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_offsides == null) {
                $model->guest_offsides = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">20</b>';
            echo $form->field($model, 'guest_offsides')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_corners == null) {
                $model->home_corners = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">30</b>';
            echo $form->field($model, 'home_corners')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 30,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_corners == null) {
                $model->guest_corners = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">30</b>';
            echo $form->field($model, 'guest_corners')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 30,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_fouls == null) {
                $model->home_fouls = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">40</b>';
            echo $form->field($model, 'home_fouls')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 40,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_fouls == null) {
                $model->guest_fouls = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">40</b>';
            echo $form->field($model, 'guest_fouls')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 40,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_yellow_cards == null) {
                $model->home_yellow_cards = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">28</b>';
            echo $form->field($model, 'home_yellow_cards')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 28,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_yellow_cards == null) {
                $model->guest_yellow_cards = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">28</b>';
            echo $form->field($model, 'guest_yellow_cards')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 28,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_red_cards == null) {
                $model->home_red_cards = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">20</b>';
            echo $form->field($model, 'home_red_cards')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_red_cards == null) {
                $model->guest_red_cards = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">20</b>';
            echo $form->field($model, 'guest_red_cards')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_goals == null) {
                $model->home_goals = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">15</b>';
            echo $form->field($model, 'home_goals')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 15,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_goals == null) {
                $model->guest_goals = 0;
            }
    
            echo '<b class="badge pull-left">0</b>';
            echo '<b class="badge pull-right">15</b>';
            echo $form->field($model, 'guest_goals')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 15,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>    
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
            if($model->home_ball_possession == null) {
                $model->home_ball_possession = 0;
            }
    
            echo '<b class="badge pull-left">0%</b>';
            echo '<b class="badge pull-right">100%</b>';
            echo $form->field($model, 'home_ball_possession')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>
    </div>
    
    <div class="col-sm-6">
        <?php
            if($model->guest_ball_possession == null) {
                $model->guest_ball_possession = 0;
            }
    
            echo '<b class="badge pull-left">0%</b>';
            echo '<b class="badge pull-right">100%</b>';
            echo $form->field($model, 'guest_ball_possession')->widget(Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                    'tooltip' => 'always',
                ],
            ]);
        ?>    
    </div>
</div>