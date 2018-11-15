<?php

    use yii\bootstrap4\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Модальное окно на доабавление лицевого счета
 */
?>
<?php
    Modal::begin([
        'id' => 'create-account-modal',
        'title' => 'Добавить лицевой счет',
        'closeButton' => [
            'class' => 'close add-acc-modal-close-btn req account-create__btn_close',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
    ]);
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'create-account-modal-form',
        'action' => ['create-account', 
            'client_id' => Yii::$app->userProfile->clientID, 
        ],
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'enableAjaxValidation' => true,
        'validationUrl' => ['validate-account-form'],
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>',
            'labelOptions' => ['class' => 'label-registration hidden'],
        ],
    ]);
?>

<?= $form->field($model, 'account_number')
        ->input('text', ['class' => 'field-input-modal'])
        ->label($model->getAttributeLabel('account_number'), ['class' => 'field-label-modal']) ?>
<?= $form->field($model, 'last_sum')
        ->input('text', ['class' => 'field-input-modal'])
        ->label($model->getAttributeLabel('last_sum'), ['class' => 'field-label-modal']) ?>
<?= $form->field($model, 'square')
        ->input('text', ['class' => 'field-input-modal'])
        ->label($model->getAttributeLabel('square'), ['class' => 'field-label-modal']) ?>

<div class="modal-footer no-border">
    <?= Html::submitButton('Создать', ['class' => 'btn blue-outline-btn white-btn mx-auto']) ?>
    <?= Html::button('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 account-create__btn_close', 'data-dismiss' => 'modal']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>