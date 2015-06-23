<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $model common\models\Question
**/

$block = \common\models\SiteBlock::getQuestionBlock($model);
echo $this->render($block['view'], $block['data']);
