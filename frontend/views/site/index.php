<?php
/**
 * @var $this yii\web\View
 * @var $templateType
 * @var $columnFirst
 * @var $columnSecond
 * @var $columnThird
**/

$this->title = isset($title) ? $title : 'Динамомания';
$templateType = isset($templateType) ? $templateType : 'col3';
$columnFirst = (isset($columnFirst)) ? $columnFirst : [];
$columnSecond = (isset($columnSecond)) ? $columnSecond : [];
$columnThird = (isset($columnThird)) ? $columnThird : [];

if($templateType == 'col3') { ?>

<?php echo $this->render('@frontend/views/site/column', [
    'classes' => 'grid-column-1',
    'blocks' => $columnFirst,
]); ?>

<?php echo $this->render('@frontend/views/site/column', [
    'classes' => 'grid-column-2',
    'blocks' => $columnSecond,
]); ?>

<?php echo $this->render('@frontend/views/site/column', [
    'classes' => 'grid-column-3',
    'blocks' => $columnThird,
]); ?>

<?php } elseif($templateType == 'col2') {?>

<?php echo $this->render('@frontend/views/site/column', [
    'classes' => 'grid-main-column',
    'blocks' => $columnFirst,
]); ?>

<?php echo $this->render('@frontend/views/site/column', [
    'classes' => 'grid-sidebar-column',
    'blocks' => $columnSecond,
]); ?>

<?php } ?>