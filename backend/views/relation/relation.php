<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use dosamigos\selectize\SelectizeDropDownList;

use common\models\League;
use common\models\Championship;
use common\models\Season;

/* @var $this yii\web\View */
/* @var $post \common\models\Post */
/* @var $relation \common\models\Relation */
?>

<div class="bind-post-to-match">
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6">
                <?php 
                    echo $form->field($matchModel, 'championship_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Championship::find()->all(), 'id', 'name'),
                        'language' => 'ru',            
                        'options' => ['placeholder' => 'Выберите турнир...', 'class' => 'match-search-item'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                ?>
                </div>
                <div class="col-sm-6">
                <?php 
                    echo $form->field($matchModel, 'season_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Season::find()->orderBy(['id' => SORT_DESC])->all(), 'id', 'name'),
                        'language' => 'ru',
                        'options' => ['placeholder' => 'Выберите сезон...', 'class' => 'match-search-item'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                <?php
                    $availableTeams = [];
                    
                    if(isset($matchModel->teamHome)) {
                        $team = $matchModel->teamHome;
                        if(isset($team->id)) {
                            $availableTeams = [$team->id => $team->name];
                        }
                    }
                
                    echo $form->field($matchModel, 'command_home_id')->widget(SelectizeDropDownList::classname(), [
                        'loadUrl' => Url::to(['team/team-list']),        
                        'items' => $availableTeams,
                        'options' => [
                            'multiple' => false,
                            'placeholder' => 'Поиск команды...',
                            'class' => 'match-search-item',
                        ],
                        'clientOptions' => [
                            'valueField' => 'value',
                            'labelField' => 'text',
                            'persist' => false,
                        ],
                    ]);
                ?>
                </div>
                <div class="col-sm-6">
                <?php
                    $availableTeams = [];
                
                    if(isset($matchModel->command_guest_id)) {
                        $team = $matchModel->teamGuest;
                        if(isset($team->id)) {
                            $availableTeams = [$team->id => $team->name];
                        }
                    }
            
                    echo $form->field($matchModel, 'command_guest_id')->widget(SelectizeDropDownList::classname(), [
                        'loadUrl' => Url::to(['team/team-list']),        
                        'items' => $availableTeams,
                        'options' => [
                            'multiple' => false,
                            'placeholder' => 'Поиск команды...',
                            'class' => 'match-search-item',
                        ],
                        'clientOptions' => [
                            'valueField' => 'value',
                            'labelField' => 'text',
                            'persist' => false,
                        ],
                    ]);
                ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php 
                        if(isset($relation->relationable_type) && 
                            $relation->relationable_type == $relation::RELATIONABLE_POST) { 
                            echo $form->field($relation, 'relation_type_id')
                                ->dropDownList($relation::dropdownRelations());
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php 
                echo $form->field($relation, 'parent_id')
                    ->checkboxList($matchesList, ['id' => 'match-list'])
                    ->label('Матч');
            ?>
        </div>
    </div>

</div>