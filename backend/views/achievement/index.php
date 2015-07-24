<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AchievementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="achievement-index">

    <?= GridView::widget([
        'summary' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'name',
                'filter' => false,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'options' => ['width' => 50],
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            $customUrl = Url::to(['/achievement/delete', 'id' => $model['id']]);
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                                'title' => 'Удалить', 
                                'data-pjax' => 0,
                                'data-confirm' => "Вы уверены, что хотите удалить этот элемент?",
                            ]);
                        },
                        'update' => function ($url, $model) {
                            $customUrl = Url::to(['/achievement/update', 'id' => $model['id']]);
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $customUrl, [
                                'title' => 'Изменить', 
                            ]);
                        },
                    ],
            ],
        ],
    ]); ?>

</div>
