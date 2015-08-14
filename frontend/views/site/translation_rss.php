<?php
/**
 * @var $this yii\web\View
 * @var $translations array
 */
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<translations>
    <?php foreach($translations as $translation) { ?>
    <translation id="<?= $translation->id ?>" competition_id="<?= $translation->competition_id ?>" event_id="<?= $translation->event_id ?>">
        <link><?= $translation->link ?></link>
        <?php foreach($translation->comments as $comment) { ?>
        <comment id="<?= $comment->id ?>">
            <time><?= $comment->time ?></time>
            <text><?= $comment->text ?></text>
        </comment>
        <?php } ?>
    </translation>
    <?php } ?>
</translations>
