<?php
    use yii\helpers\Html;
/* 
 * Форма письма для подтверждения регистрации
 */
$link = Yii::$app->urlManager->createAbsoluteUrl(['email-confirm', 'token' => $user->email_confirm_token]);
?>
Здравствуйте, <?= $user->user_login ?>
<br />
Для подтверждения вашей рагистрации пройдите по ссылке 
<?= Html::a(Html::encode($link), $link) ?>

