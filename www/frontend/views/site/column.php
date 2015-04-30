<?php
/**
 * @var $this yii\web\View
 * @var $blocks[] array of render blocks
 * @var $block['view'] name of view to render
 * @var $block['data'] models and other data
 * @var $classes
**/

?>
<div class="grid-column <?=$classes?>">
<?php
    foreach ($blocks as $block) {
        echo $this->render($block['view'], isset($block['data']) ? $block['data'] : []);
    }
?>
</div>