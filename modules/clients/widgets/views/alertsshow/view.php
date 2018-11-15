<?php

    use kartik\alert\Alert;

/*
 * Вид вывода сообщений для пользователей
 */    
?>

<?php if (Yii::$app->session->hasFlash('success')) : ?>    
    <?=
        Alert::widget([
            'type' => Alert::TYPE_INFO,
            'title' => 'Профиль обновлен',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => Yii::$app->session->getFlash('success', false),
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>
    
<?php if (Yii::$app->session->hasFlash('error')) : ?>    
    <?=
        Alert::widget([
            'type' => Alert::TYPE_DANGER,
            'title' => 'Ошибка обновления профиля',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => Yii::$app->session->getFlash('error', false),
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?> 

<?php /* Профиль пользователя */
    if (Yii::$app->session->hasFlash('profile')) : ?>
    <?=
        Alert::widget([
            'type' => Alert::TYPE_INFO,
            'title' => 'Профиль пользователя',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => 'Данные вашего профиля были успешно обновлены',
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php elseif (Yii::$app->session->hasFlash('profile')) : ?>
    <?=
        Alert::widget([
            'type' => Alert::TYPE_DANGER,
            'title' => 'Профиль пользователя',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>



<?php /* Заявки */
    if (Yii::$app->session->hasFlash('request')) : ?>
    <?=
        Alert::widget([
            'type' => $flash['success'] ? Alert::TYPE_INFO : Alert::TYPE_DANGER,
            'title' => 'Заявки',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $flash['success'] ? $flash['message'] : $flash['error'],
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('paid-services')) : ?>
<?php $flash = Yii::$app->session->getFlash('paid-services'); ?>
    <?=
        Alert::widget([
            'type' => $flash['success'] ? Alert::TYPE_INFO : Alert::TYPE_DANGER,
            'title' => 'Платные услуги',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $flash['success'] ? $flash['message'] : $flash['error'],
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('form')) : ?>
<?php $flash = Yii::$app->session->getFlash('form'); ?>
    <?=
        Alert::widget([
            'type' => $flash['success'] ? Alert::TYPE_INFO : Alert::TYPE_DANGER,
            'title' => 'Лицевой счет',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $flash['message'],
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>