<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var bool $success
 */
?>
    <?php if ($success): ?>

        <div class="default-box alert-box alert-success">
            <div class="box-content">
                <div class="message">
                    <p><?= Yii::t("user", "Your email [ {email} ] has been confirmed", ["email" => $success]) ?></p>

                    <?php if (Yii::$app->user->isLoggedIn): ?>

                        <p><?= Html::a(Yii::t("user", "Go to my account"), ["/user/account"]) ?></p>

                    <?php else: ?>

                        <p><?= Html::a(Yii::t("user", "Log in here"), ["/user/login"]) ?></p>

                    <?php endif; ?>
                </div>
                <div class="desc">Этот блок закроется через <span class="sec">30</span> секунд</div>
                <div class="close">×</div>
            </div>
        </div>

    <?php else: ?>

        <div class="default-box alert-box alert-error">
            <div class="box-content">
                <div class="message"><?= Yii::t("user", "Invalid key") ?></div>
                <div class="desc">Этот блок закроется через <span class="sec">60</span> секунд</div>
                <div class="close">×</div>
            </div>
        </div>

    <?php endif; ?>

