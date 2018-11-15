<?php

    use yii\bootstrap4\Modal;
    use yii\helpers\Html;

/* 
 * Модальное окно, активизируется, когда нажат checkbox Арендатор 
 * Профиль Собственника
 */ 
?>

<?php
Modal::begin([
    'id' => 'changes_rent',
    'title' => 'Внимание!',
    'closeButton' => [
        'class' => 'close add-acc-modal-close-btn req changes_rent__close',
    ],
    'clientOptions' => [
        'backdrop' => 'static', 
        'keyboard' => false,
    ],
]);
?>

    <p class="del-ar">Данные арендатора будут удалены! Продолжить?</p>

    <div class="modal-footer no-border">
        <?= Html::button('Удалить', ['class' => 'btn blue-outline-btn white-btn mx-auto changes_rent__del']) ?>
        <?= Html::button('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 changes_rent__close', 'data-dismiss' => 'modal']) ?>
    </div>

<?php Modal::end(); ?>