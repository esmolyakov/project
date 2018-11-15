<?php

    use yii\bootstrap4\Modal;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
/* 
 * Форма в модальном окне, создание новой заявки на платную услугу
 */
?>
<?php
    Modal::begin([
        'id' => 'add-record-modal',
        'title' => 'Заявка на платную услугу',
        'closeButton' => [
            'class' => 'close add-acc-modal-close-btn req btn__paid_service_close',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
    ]);
?>
    <?php
        $form = ActiveForm::begin([
            'id' => 'add-paid-service',
            'validateOnChange' => false,
            'validateOnBlur' => false,
        ]);
    ?>
    
    <?= $form->field($new_order, 'services_category_services_id')
            ->hiddenInput(['id' => 'secret-cat', 'value' => 'hidden value'])
            ->label(false) ?>
    
    <?= $form->field($new_order, 'services_name_services_id')
            ->hiddenInput(['id' => 'secret-name', 'value' => 'hidden value'])
            ->label(false) ?>
                    
    <?= $form->field($new_order, 'services_name_services_id', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($name_services_array, [
                'id' => 'name_services',
                'class' => 'form-control name_services',
                'disabled' => true])
            ->label(false) ?>

    <?= $form->field($new_order, 'services_phone', ['template' => '<div class="field-modal">{label}{input}{error}</div>'])
            ->widget(MaskedInput::className(), [
                'mask' => '+7(999) 999-99-99'])
            ->input('text', ['class' => 'field-input-modal phone'])
            ->label($new_order->getAttributeLabel('services_phone'), ['class' => 'field-label-modal']) ?>
                
    <?= $form->field($new_order, 'services_comment', ['template' => '<div class="field-modal-textarea">{label}{input}{error}</div>'])
            ->textarea([
                'rows' => 10,
                'class' => 'field-input-textarea-modal comment'])
            ->label($new_order->getAttributeLabel('services_comment'), ['class' => 'field-label-modal']) ?>
    
    <div class="modal-footer no-border">
        <?= Html::submitButton('Добавить', ['class' => 'btn blue-outline-btn white-btn mx-auto']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 btn__paid_service_close', 'data-dismiss' => 'modal']) ?>
    </div>
    
    <?php ActiveForm::end() ?>    
    
<?php Modal::end(); ?>