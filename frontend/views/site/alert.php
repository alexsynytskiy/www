<?php
/**
 * @var $this yii\web\View
**/

foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
    $classes = explode('-', $key);
    $class = array_shift($classes);
?>
    <div class="default-box alert-box alert-<?= $class ?>">
        <div class="box-content">
            <div class="message"><?= $message ?></div>
            <div class="desc">Этот блок закроется через <span class="sec">16</span> секунд</div>
            <div class="close">×</div>
        </div>
    </div>
    <?php } ?>