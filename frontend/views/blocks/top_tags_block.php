<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $topTags Array of common\models\TagsCloud
**/
?>
<div class="tags-cloud">
    
    <?php
    $fontSize = 14;

    foreach ($topTags as $tag) {
        if($tag->weight == 10) {
            $fontSize = 25;
        }
        else if($tag->weight == 8) {
            $fontSize = 22;
        }
        else if($tag->weight == 6) {
            $fontSize = 19;
        }
        else if($tag->weight == 4) {
            $fontSize = 16;
        }
        else if($tag->weight == 2) {
            $fontSize = 14;
        }
        else {
            $fontSize = 12;
        }
    ?>
        <a href="/search?t=<?= $tag->tag->name ?>" style="font-size:<?= $fontSize ?>px"><?= $tag->tag->name ?></a>
    <?php } ?>
</div>