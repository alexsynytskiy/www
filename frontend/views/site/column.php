<?php
use common\models\Banner;
use common\models\SiteBlock;

/**
 * @var $this yii\web\View
 * @var $blocks[] array of render blocks
 * @var $block['view'] name of view to render
 * @var $block['data'] models and other data
 * @var $classes
**/

$isSmall = true;
switch ($classes) {
    case 'grid-column-1':
        $region = Banner::REGION_FIRST_COLUMN;
        break;
    case 'grid-column-2':
        $region = Banner::REGION_SECOND_COLUMN;
        break;
    case 'grid-column-3':
    case 'grid-sidebar-column':
        $region = Banner::REGION_THIRD_COLUMN;
        break;
    case 'grid-main-column':
        $region = Banner::REGION_FIRST_COLUMN;
        $isSmall = false;
        break;
    default:
        $region = 0;
        break;
}

?>
<div class="grid-column <?=$classes?>">
<?php
    if($classes == 'grid-column-2' || $classes == 'grid-sidebar-column') {
        echo $this->render('@frontend/views/site/alert');
    }
    if($region == Banner::REGION_THIRD_COLUMN) {
        $bannerBlock = SiteBlock::getBanner(Banner::REGION_TOP_THIRD_COLUMN);
        if($bannerBlock) {
            echo $this->render($bannerBlock['view'], isset($bannerBlock['data']) ? $bannerBlock['data'] : []);
        }
    }

    foreach ($blocks as $block) {
        if($block) {
            echo $this->render($block['view'], isset($block['data']) ? $block['data'] : []);
        }
    }
?>
</div>