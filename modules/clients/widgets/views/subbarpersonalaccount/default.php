<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Дополнительное навигационное меню в разделе "Лицевой счет"
 */
?>

<?php if (Yii::$app->controller->id == 'personal-account') : ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light navbar_personal-account my-navbar">
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['personal-account/index']) ?>">
                    Общая информация
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['personal-account/payment']) ?>">
                    Платежи
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['personal-account/counters']) ?>">
                    Показания приборов учета
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['personal-account/receipts-of-hapu']) ?>">
                    Квитанция ЖКУ
                </a>
            </li>            
        </ul>
  </div>
</nav>
<?php endif; ?>