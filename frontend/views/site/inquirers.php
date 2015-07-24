<?php
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
**/
?>

<div class="inquirers-container">
    
<?php
    echo \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'inquirer-item'],
        'itemView' => '@frontend/views/site/inquirer_item',
        'pager' => [
            'class' => \kop\y2sp\ScrollPager::className(),
            'item' => '.inquirer-item',
            'delay' => 0,
            'noneLeftText' => 'Больше нет опросов',
            'triggerOffset' => 100,
            'spinnerTemplate' => '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>',
            'eventOnRendered' => new JsExpression("function(items){
                $('.inquirers-container').indyMasonry('_newElement');
            }"),
         ],
         'summary' => '', 
    ]);
?>
<div class="clearfix"></div>
</div>
