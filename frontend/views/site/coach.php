<?php
use yii\helpers\Url;

use common\models\Amplua;

/**
 * @var $this yii\web\View
 * @var $coach common\models\Player
 * @var $image common\models\Asset
**/
Yii::$app->formatter->locale = 'ru-RU';

?>

<div class="default-box profile">    
    <div class="box-content">
        <div class="photo-block">
            <div class="photo">
                <img src="<?= $image->getFileUrl() ?>">
            </div>            
        </div>
        <div class="about">
            <div class="name"><?= $coach->name ?></div>
             <div class="feature">
                <div class="title">Дата рождения: </div>
                <div class="text"><?= Yii::$app->formatter->asDate($coach->birthday,'dd.MM.Y') ?></div>
            </div>
            <div class="clearfix"></div>         
            <div class="text-about-person">
            <?php 
                if(isset($coach->coach_carrer) && trim($coach->coach_carrer) != '') {
                    echo $coach->coach_carrer;
                }
                else if(isset($coach->player_carrer) && trim($coach->player_carrer) != ''){
                    echo $coach->player_carrer;
                }
                else if(isset($coach->notes) && trim($coach->notes) != ''){
                    echo $coach->notes;
                }
            ?>
            </div>      
        </div>
    </div>
</div>

<?php if(isset($coach->coach_carrer) && trim($coach->coach_carrer) != '' && 
        isset($coach->player_carrer) && trim($coach->player_carrer) != '') { ?>
    <div class="default-box coach-player-career">
        <div class="box-header">
            <div class="main-title">Карьера игрока</div>
        </div> 
        <div class="box-content">
            <?= $coach->player_carrer ?>
        </div>
    </div>
<?php } ?>

<?php if((isset($coach->coach_carrer) && trim($coach->coach_carrer) != '' || 
        isset($coach->player_carrer) && trim($coach->player_carrer) != '') && 
        isset($coach->notes) && trim($coach->notes) != '') { ?>
    <div class="default-box coach-biography">
        <div class="box-header">
            <div class="main-title">Биография</div>
        </div> 
        <div class="box-content">
            <?= $coach->notes ?>
        </div>
    </div>
<?php } ?>