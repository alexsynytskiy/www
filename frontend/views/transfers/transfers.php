<?php
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var $transferTypesData array Array of transfer types
 * @var $seasonsData array Array of available seasons
**/
?>

<div class="search-box default-box transfers-search" style="min-height: 0;">
    <div class="box-content">
        <form class="search-matches" action="<?= Url::to(['/site/transfers']) ?>">
            <div class="select-championship selectize-box">
                <label for="select-championship">Выбрать тип трансферов</label>
                <select name="transfer-type" id="select-transfer-type" placeholder="Выбрать тип трансферов">
                    <?php foreach ($transferTypesData as $transferType) {
                        $active = ($transferType->active) ? 'selected class="data-default"' : '';
                    ?>
                        <option value="<?= $transferType->value ?>" <?= $active ?>><?= $transferType->text ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="select-season selectize-box">
                <label for="select-season">Выбрать сезон</label>
                <select name="season" id="select-season" placeholder="Выбрать сезон">
                    <?php foreach ($seasonsData as $season) {
                        $active = ($season->active) ? 'selected class="data-default"' : '';
                    ?>
                        <option value="<?= $season->value ?>" <?= $active ?>><?= $season->text ?></option>
                    <?php } ?>
                </select>
            </div>
        </form>
    </div>
</div>

<?php
    foreach ($transferTypesData as $transferType) {
        if(is_numeric($transferType->value)){
            $selectedTransfers = [];
            foreach ($transfers as $transfer) {
                if($transfer->transfer_type_id == $transferType->value) {
                    $selectedTransfers[] = $transfer;
                }
            }
            if(count($selectedTransfers) > 0) {
                $firstTransfer = $selectedTransfers[0];
                $className = $firstTransfer->getTransferTypeAbr();
                echo $this->render('@frontend/views/transfers/transfer_table', [
                    'transfers' => $selectedTransfers,
                    'title' => $transferType->text,
                    'className' => $className,
                ]);
            }
        }
    }
?>
