<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/* 
 * Быстрый доступ к профилю пользователя 
 */

?>
<li class="nav-item dropdown">
    <a class="nav-link text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= Html::img('/images/navbar/user.svg') ?>
    </a>
    <ul class="dropdown-menu">
        <li class="user-info-box">
            <div class="row">
                <div class="col-lg-5 col-sm-5 col-5 text-center">
                    <a href="<?= Url::to(['profile/index']) ?>">
                        <?= Html::img(Yii::$app->userProfile->photo, ['class' => 'rounded-circle photo-user-dropdown']) ?>                        
                    </a>
                </div>
                <div class="col-lg-6 col-sm-6 col-6 dropdown_user-info">
                    <p class="dropdown_user-name">
                        <?= Yii::$app->userProfile->fullNameClient ?>                        
                    </p>
                    <div class="dropdown-menu_link-profile">
                        <?= Html::a('Мой профиль', ['profile/index']) ?>
                    </div>                    
                    <div class="mail-border">
                        <p class="mail-color">
                            <?= Yii::$app->userProfile->email ?> 
                        </p>
                    </div>
                    <div class="dropdown_account-block">
                        <p class="dropdown_account-title">Текущий лицевой счет</p>
                        <p class="dropdown_account-number"><?= $account_number ?></p>                        
                    </div>
                </div>
            </div>
        </li>
        <li class="text-light dropdown_footer">
            <div class="col-lg-12 col-sm-12 col-12">
                <?= Html::a('<i class="fa fa-lock" aria-hidden="true"></i> Изменить пароль', ['profile/settings-profile'], ['class' => 'footer_link']) ?>
                <?= Html::a('Выйти <i class="fa fa-sign-out" aria-hidden="true"></i>', ['/site/logout'], [
                        'data' => [
                            'method' => 'post'], 
                        'class' => 'float-right footer_link-logout']) ?>
            </div>
        </li>
    </ul>
</li>