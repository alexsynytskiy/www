<?php 
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $blocks array 
**/
?>
<div class="additional-data">
<?php
    foreach ($blocks as $block) {
        if($block) {
            echo '<div class="data-box">';
            echo $this->render($block['view'], isset($block['data']) ? $block['data'] : []);
            echo '</div>';
        }
    }
?>
</div>