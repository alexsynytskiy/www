<?php
use yii\helpers\Url;
/**
 * @var $this yii\web\View
 * @var $tags common\models\Tag
**/
?>

<div class="all-tags default-box">
    <div class="box-header">
        <div class="main-title">Все теги</div>
    </div>  
    <div class="box-content">
        <?php
        
        foreach($tags as $tag) {            
        ?>
            <a href="/search?t=<?= $tag->name ?>"><?= $tag->name ?></a>
    <?php } ?>
    </div>
</div>