<?php
/**
 * @var $this yii\web\View
 * @var $title string
 * @var $description string
 * @var $items array
 */
$baseUrl = 'http://'.$_SERVER['HTTP_HOST'];
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
    <channel>
        <title><?= $title ?></title>
        <link><?= $baseUrl ?></link>
        <description><?= $description ?></description>
        <yandex:logo><?= $baseUrl ?>/images/favicon.png</yandex:logo>
        <yandex:logo type="square"><?= $baseUrl ?>/images/favicon.png</yandex:logo>
        <language>ru</language>
        <?php foreach ($items as $item) { ?>
            <item>
                <title><?= $item->title ?></title>
                <link><?= $baseUrl.$item->link ?></link>
                <description><?= $item->description ?></description>
                <yandex:full-text><?= $item->fulltext ?></yandex:full-text>
                <?php if($item->authorName) { ?>
                <author><?= $item->authorName ?></author>
                <?php } ?>
                <yandex:genre>message</yandex:genre>
                <pubDate><?= $item->pubDate ?></pubDate>
                <?php if($item->enclosureUrl && $item->enclosureType) { ?>
                <enclosure url="<?= $item->enclosureUrl ?>" type="<?= $item->enclosureType ?>"/>
                <?php } ?>
            </item>
        <?php } ?>
    </channel>
</rss>