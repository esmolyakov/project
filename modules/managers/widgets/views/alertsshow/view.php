<?php

    use kartik\alert\Alert;

/*
 * Вид вывода сообщений для пользователей
 */    
?>

<?php if (Yii::$app->session->hasFlash('profile-admin')) : ?>
    <?=
        Alert::widget([
            'type' => Alert::TYPE_INFO,
            'title' => 'Профиль пользователя',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => 'Данные профиля были успешно обновлены',
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php elseif (Yii::$app->session->hasFlash('profile-admin-error')) : ?>
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

<?php if (Yii::$app->session->hasFlash('delete-rent')) : ?>
<?php $flash = Yii::$app->session->getFlash('delete-rent'); ?>
    <?=
        Alert::widget([
            'type' => $flash['success'] ? Alert::TYPE_INFO : Alert::TYPE_DANGER,
            'title' => 'Удаление ученой записи арендатора',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $flash['success'] ? $flash['message'] : $flash['error'],
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('delete-employer')) : ?>
<?php $flash = Yii::$app->session->getFlash('delete-employer'); ?>
    <?=
        Alert::widget([
            'type' => $flash['success'] ? Alert::TYPE_INFO : Alert::TYPE_DANGER,
            'title' => 'Удаление ученой записи сотрудника',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $flash['success'] ? $flash['message'] : $flash['error'],
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('services-admin')) : ?>
<?php $flash = Yii::$app->session->getFlash('services-admin'); ?>
    <?=
        Alert::widget([
            'type' => $flash['success'] ? Alert::TYPE_INFO : Alert::TYPE_DANGER,
            'title' => 'Услуги',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $flash['success'] ? $flash['message'] : $flash['error'],
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('news-admin')) : ?>
<?php $flash = Yii::$app->session->getFlash('news-admin'); ?>
    <?=
        Alert::widget([
            'type' => $flash['success'] ? Alert::TYPE_INFO : Alert::TYPE_DANGER,
            'title' => 'Публикации',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $flash['success'] ? $flash['message'] : $flash['error'],
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('voting-admin')) : ?>
<?php $flash = Yii::$app->session->getFlash('voting-admin'); ?>
    <?=
        Alert::widget([
            'type' => $flash['success'] ? Alert::TYPE_INFO : Alert::TYPE_DANGER,
            'title' => 'Голосование',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $flash['success'] ? $flash['message'] : $flash['error'],
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('estate-admin')) : ?>
<?php $flash = Yii::$app->session->getFlash('estate-admin'); ?>
    <?=
        Alert::widget([
            'type' => $flash['success'] ? Alert::TYPE_INFO : Alert::TYPE_DANGER,
            'title' => 'Жилой комплекс',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $flash['success'] ? $flash['message'] : $flash['error'],
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>