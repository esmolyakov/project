<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Полный профиль пользователя на странице "Настройки"
 */
?>
<div class="text-center">
    <?= Html::img($user_info->photo, ['id' => 'photoPreview','class' => 'img-circle', 'alt' => $user_info->username, 'width' => 150]) ?>
</div>
<table class="table table-profile">
    <tr>
        <td>
            <p>Фамилия, имя, отчество</p>
            <?=  $user_info->surname . ' '. $user_info->name . ' ' . $user_info->secondName ?>
        </td>
    </tr>
    <tr>
        <td>
            <p>Роль</p>
            <?= $user_info->role ?>
        </td>
    </tr>
    <tr>
        <td>
            <p>Логин</p>
            <?= $user_info->username ?>
        </td>
    </tr>
    <tr>
        <td>
            <p>Дата регистрации</p>
            <?= FormatHelpers::formatDate($user_info->dateRegister, true, 0, false) ?>
        </td>
    </tr>
    <tr>
        <td>
            <p>Дата последнего входа</p>
            <?= FormatHelpers::formatDate($user_info->lastLogin, true, 0, false) ?>
        </td>
    </tr>
    <tr>
        <td>
            <p>Статус учетной записи</p>
            <?= $user_info->getStatus() ?>
        </td>
    </tr>
</table>