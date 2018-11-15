<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/* 
 * Вывод новостей в личном кабинете собственника
 */
?>
<?php if (Yii::$app->controller->id == 'clients' && Yii::$app->controller->action->id == 'index') : ?>

    <?php $current_block = Yii::$app->controller->actionParams['block']; ?>

    <?php if (isset($general_navbar)) : ?>
    <nav class="navbar nav-pills mx-auto text-center justify-content-center nav-tref-potty">
        <ul class="nav nav-pills mx-auto text-center justify-content-center">
            <?php foreach ($general_navbar as $key => $item) : ?>
                <li class="nav-item">
                    <a href="<?= Url::to(['clients/index', 'block' => $key]) ?>" 
                       class="nav-link 
                            submenu-nav-link 
                            <?= ($current_block == $key) ? 'active' : '' ?> 
                            <?= ($key == 'special_offers') ? ' no-border-right no-border-left' : '' ?>
                            <?= ($key == 'special_offers') ? 'central-block' : '123' ?>"
                    >
                        <?= $item ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php endif; ?>

<?php endif; ?>