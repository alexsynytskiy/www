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
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
  <title><?= $title ?></title>
  <link><?= $baseUrl ?></link>
  <description><?= $description ?></description>
  <?php foreach ($items as $item) { ?>
    <item>
      <title><?= $item->title ?></title>
      <link><?= $baseUrl.$item->link ?></link>
      <description><?= $item->description ?></description>
      <pubDate><?= $item->pubDate ?></pubDate>
    </item>
  <?php } ?>
</channel>
</rss> 