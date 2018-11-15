<?php

    use yii\bootstrap4\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;

/* 
 * Модальное окно добавление учетной записи арендатора
 */
?>
<?php
Modal::begin([
    'id' => 'add-rent-modal',
    'title' => 'Создание учетной записи арендатора',
    'closeButton' => [
        'class' => 'close add-acc-modal-close-btn req rent-info__btn_close',
    ],
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => false,
    ],
]);
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'create-rent-form',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'action' => ['create-rent-form', 
            'client' => Yii::$app->userProfile->clientID, 
        ],
        'enableAjaxValidation' => true,
        'validationUrl' => ['validate-rent-form'],
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>',
            'labelOptions' => ['class' => 'label-registration hidden'],
        ],        
    ]);
?>

    <?= $form->field($add_rent, 'rents_surname')
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($add_rent->getAttributeLabel('rents_surname'), [
                'class' => 'field-label-modal']) ?>

    <?= $form->field($add_rent, 'rents_name')
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($add_rent->getAttributeLabel('rents_name'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($add_rent, 'rents_second_name')
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($add_rent->getAttributeLabel('rents_second_name'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($add_rent, 'rents_mobile')
            ->widget(MaskedInput::className(), [
                'mask' => '+7 (999) 999-99-99'])
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($add_rent->getAttributeLabel('rents_mobile'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($add_rent, 'rents_email')
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($add_rent->getAttributeLabel('rents_email'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($add_rent, 'password')
            ->input('password', [
                'class' => 'field-input-modal show_password'])
            ->label($add_rent->getAttributeLabel('password'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($add_rent, 'password_repeat')
            ->input('password', [
                'class' => 'field-input-modal show_password'])
            ->label($add_rent->getAttributeLabel('password_repeat'), ['class' => 'field-label-modal']) ?>

    <div class="el-checkbox">
        <?= Html::checkbox('show_password_ch', false, ['id' => '1_1']) ?>
        <label class="el-checkbox-style" for="1_1"></label>
        <span class="margin-l">Показать пароли</span>
    </div>
    
    <div class="modal-footer no-border">
        <?= Html::submitButton('Создать', ['class' => 'btn blue-outline-btn white-btn mx-auto']) ?>
        <?= Html::button('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 rent-info__btn_close', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>