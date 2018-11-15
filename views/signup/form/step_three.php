<?php

    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\helpers\Html;
/* 
 * Регистрация, шаг 3
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'signup-form-step-two',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => '{label}{input}',
            'labelOptions' => ['class' => 'label-registration hidden'],
        ],
        'options' => [
            'class' => 'form-signin d-block my-auto material',
        ],
    ])
?>

    <?= $form->errorSummary($model_step_three); ?>

    <div class="field">
        <?= $form->field($model_step_three, 'phone')
                ->widget(MaskedInput::className(), [
                    'mask' => '+7 (999) 999-99-99'])
                ->input('text', ['class' => 'field-input'])
                ->label($model_step_three->getAttributeLabel('phone'), ['class' => 'field-label']) ?> 
        <?= Html::button('Получить код', ['id' => 'send-request-to-sms']) ?>
    </div>

    <div class="field-sms">
        <?= $form->field($model_step_three, 'sms_code')
                ->widget(MaskedInput::className(), ['mask' => '9{5,5}'])
                ->input('text', ['class' => 'field-input'])
                ->label($model_step_three->getAttributeLabel('sms_code'), ['class' => 'field-label']) ?>
        
        
    </div>

    <div class="text-center circle-btn-block mx-auto">
        <p>Нажимая на кнопку Далее, вы соглашаетесь на обработку 
        <a href="#" target="_blank">Персональных данных </a>и принимаете условия <a href="#" target="_blank">Пользовательского соглашения.</a>
        </p>

        <?= Html::submitButton('', ['class' => 'blue-circle-btn']) ?>    
    </div>


<?php ActiveForm::end(); ?>