<?php
/* @var $this yii\web\View */
$this->title = Yii::t('user', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('@webroot/../views/site/small_column', [
    'classes' => 'grid-column-1',
    'blocks' => [
        ['name' => 'block1'],
    ],
]); ?>

<?php echo $this->render('@webroot/../views/site/small_column', [
    'classes' => 'grid-column-2',
    'blocks' => [
        ['name' => '/default/blocks/register_block', 'data' => ['user' => $user, 'profile' => $profile] ],
    ],
]); ?>

<?php echo $this->render('@webroot/../views/site/small_column', [
    'classes' => 'grid-column-3',
    'blocks' => [
        ['name' => 'block1'],
    ],
]); ?>