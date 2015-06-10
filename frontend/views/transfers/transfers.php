<?php
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var $transferTypesData array Array of transfer types
 * @var $seasonsData array Array of available seasons
**/
?>

<?php Pjax::begin([
    'id' => 'transfers',
]); ?>
<div class="search-box default-box" style="min-height: 0;">
    <div class="box-content">
        <form class="search-matches" action="">
            <div class="select-championship selectize-box">
                <label for="select-championship">Выбрать тип трансферов</label>
                <select name="transfer-type" id="select-transfer-type" placeholder="Выбрать тип трансферов">
                    <?php foreach ($transferTypesData as $transferType) {
                        $active = ($transferType['active']) ? 'selected class="data-default"' : '';
                    ?>
                        <option value="<?= $transferType['value'] ?>" <?= $active ?>><?= $transferType['text'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="select-season selectize-box">
                <label for="select-season">Выбрать сезон</label>
                <select name="season" id="select-season" placeholder="Выбрать сезон">
                    <?php foreach ($seasonsData as $season) {
                        $active = ($season['active']) ? 'selected class="data-default"' : '';
                    ?>
                        <option value="<?= $season['value'] ?>" <?= $active ?>><?= $season['text'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </form>
    </div>
</div>

<?php
    if(isset($buyTransfers)) 
        echo $this->render('@frontend/views/transfers/transfer_table', compact('buyTransfers'));
    if(isset($sellTransfers)) 
        echo $this->render('@frontend/views/transfers/transfer_table', compact('sellTransfers')); 
    if(isset($rentTransfers)) 
        echo $this->render('@frontend/views/transfers/transfer_table', compact('rentTransfers'));
?>

<?php Pjax::end(); ?>