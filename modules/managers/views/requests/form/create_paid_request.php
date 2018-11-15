<?php

    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\helpers\Html;

/* 
 * Модальное окно "Создание заявки на платную услугу"
 */

?>
<div class="modal fade" id="create-new-paid-requests" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title">Новая заявка</h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    <?php
                        $form = ActiveForm::begin([
                            'id' => 'create-new-paid-request',
                            'action' => ['create-paid-request'],
                            'enableAjaxValidation' => true,
                            'validationUrl' => ['validation-form', 'form' => 'paid-request'],
                        ])
                    ?>

                    <?= $form->field($model, 'servise_category')
                            ->dropDownList($servise_category, [
                                'prompt' => 'Выбрать из списка...',
                                'id' => 'category_service'])
                            ->label() ?>
                    
                    <?= $form->field($model, 'servise_name')
                            ->dropDownList($servise_name, [
                                'prompt' => 'Выбрать из списка...',
                                'id' => 'service_name'])
                            ->label() ?>

                    <?= $form->field($model, 'phone')
                            ->widget(MaskedInput::className(), [
                                'mask' => '+7 (999) 999-99-99'])
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('phone'),
                                'class' => 'form-control mobile_phone'])
                            ->label() ?>
                    
                    <?= $form->field($model, 'flat')
                            ->dropDownList($flat, [
                                'class' => 'form-control house',
                                'prompt' => 'Выбрать из списка...'])
                            ->label() ?>
                    
                    <?= $form->field($model, 'description')->textarea()->label() ?>

                    <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
                    <?= Html::button('Отмена', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>

                    <?php ActiveForm::end() ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>