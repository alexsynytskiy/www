<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $transfer common\models\Transfer 
**/

$className = $transfer->getTransferTypeAbr();
$title = $transfer->transferType->name;

echo $this->render('@frontend/views/transfers/transfer_table', [
    'transfers' => [$transfer],
    'title' => $title,
    'className' => $className,
]);

?>

