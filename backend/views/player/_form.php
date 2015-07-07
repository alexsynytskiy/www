<?php

use common\models\Amplua;
use common\models\Country;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Player */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="player-form">

    <?php $form = ActiveForm::begin(['options' => [
        'autocomplete' => 'off',
        'enctype' => 'multipart/form-data',
    ]]); ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => 255]) ?>

    <?php
    $pluginOptions = [
        'showUpload' => false,
        'showRemove' => false,
        'overwriteInitial' => true,
        'browseLabel' => "Обзор...",
        'allowedFileExtensions' => ['jpeg', 'jpg', 'gif', 'png'],
    ];
    $avatar = $model->getAsset();
    if (!$model->isNewRecord && $avatar->getFileUrl())
    {
        $pluginOptions['initialPreview'] = [
            Html::img($avatar->getFileUrl()),
        ];
    }
    echo $form->field($model, 'avatar')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => false,
            'class' => 'jcrop',
        ],
        'pluginOptions' => $pluginOptions,
    ]);
    echo $form->field($model, 'cropData')->hiddenInput(['id' => 'crop-data'])->label(false);
    ?>

    <?php
        echo $form->field($model, 'birthday')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Выберите день рождения'],
            'removeButton' => false,
            'language' => 'ru-RU',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ]
        ]);
    ?>

    <?= $form->field($model, 'height')->textInput() ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <?php 
        echo $form->field($model, 'amplua_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Amplua::find()->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите амплуа...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?= $form->field($model, 'more_ampluas')->textInput(['maxlength' => 255]) ?>

    <?php 
        echo $form->field($model, 'country_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Country::find()->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите страну...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?php 
    echo $form->field($model, 'notes')->widget(\vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted',
                'unorderedlist', 'orderedlist', 'outdent', 'indent',
                'image', 'link', 'alignment', 'horizontalrule'],
            'plugins' => [
                'fullscreen',
                'table',
                'video',
                'fontcolor',
            ]
        ]
    ]);
    ?>

    <?php if(!$model->isNewRecord) { ?>
        <?= $this->render('/achievement/index', [
            'searchModel' => $achievementModel,
            'dataProvider' => $achievementDataProvider,
        ]) ?>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if(!$model->isNewRecord) { ?>
            <?= Html::a('Добавить достижение', ['/achievement/create', 'playerId' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
