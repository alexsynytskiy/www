<?php
<<<<<<< Updated upstream

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var common\modules\user\models\forms\LoginForm $model
 */

=======
/* @var $this yii\web\View */
>>>>>>> Stashed changes
$this->title = Yii::t('user', 'Login');
$this->params['breadcrumbs'][] = $this->title;

?>

<<<<<<< Updated upstream
</div>
=======
<?php echo $this->render('@webroot/../views/site/small_column', [
    'classes' => 'grid-column-1',
    'blocks' => [
        ['name' => 'block1'],
    ],
]); ?>

<?php echo $this->render('@webroot/../views/site/small_column', [
    'classes' => 'grid-column-2',
    'blocks' => [
        ['name' => '/default/blocks/login_block', 'data' => ['user' => $user] ],
    ],
]); ?>

<?php echo $this->render('@webroot/../views/site/small_column', [
    'classes' => 'grid-column-3',
    'blocks' => [
        ['name' => 'block1'],
    ],
]); ?>
>>>>>>> Stashed changes
