<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\modules\clients\models\form\SMSForm;
    use yii\widgets\MaskedInput;

/* 
 * Смена пароля учетной записи пользователя
 */

?>
<?php /* Форма смены пароля */ ?>
<?php if ($is_change_password == false) : ?>
    <?php
        $form_psw = ActiveForm::begin([
            'id' => 'change-password-form',
            'validateOnBlur' => false,
            'validateOnChange' => false,
            'fieldConfig' => [
                'template' => '<div class="field">{label}{input}{error}</div>',
                'labelOptions' => ['class' => 'label-registration hidden'],
            ],
        ]);
    ?>

        <?= $form_psw->field($model_password, 'current_password')
                ->input('password', ['class' => 'field-input show_password']) 
                ->label($model_password->getAttributeLabel('current_password'), ['class' => 'field-label'])
        ?>

        <?= $form_psw->field($model_password, 'new_password')
                ->input('password', ['class' => 'field-input show_password'])
                ->label($model_password->getAttributeLabel('new_password'), ['class' => 'field-label'])
        ?>

        <?= $form_psw->field($model_password, 'new_password_repeat')
                ->input('password', ['class' => 'field-input show_password'])
                ->label($model_password->getAttributeLabel('new_password_repeat'), ['class' => 'field-label'])
        ?>
        
        <div class="text-right">
                <?= Html::submitButton('Продолжить', ['class' => 'blue-outline-btn req-table-btn']) ?>
        </div>    

    <?php ActiveForm::end(); ?>

<?php endif; ?>

<?php /* Форма ввода СМС кода */ ?>
<?php if ($is_change_password == true) : ?>
    <?php
        $form_psw = ActiveForm::begin([
            'id' => 'sms-form',
            'validateOnBlur' => false,
            'validateOnChange' => false,
            'enableAjaxValidation' => true,
            'validationUrl' => ['validate-sms-form'],
            'options' => [
                'class' => 'form-horizontal',
            ],
        ]);
    ?>

    <div class="field-sms">
        <?= $form_psw->field($sms_model, 'sms_code')
                ->widget(MaskedInput::className(), [
                    'mask' => '9{5,5}'])
                ->input('text', ['class' => 'field-input input-sms_code'])
                ->label($sms_model->getAttributeLabel('sms_code'), ['class' => 'field-label'])
        ?>
    </div>        
    <div class="block-of-repeat"><span id="time-to-send"></span></div>    
    
    <div class="text-left">
        <?= Html::submitButton('Продолжить', ['class' => 'blue-outline-btn req-table-btn']) ?>        
    </div>

    <?php ActiveForm::end(); ?>

<?php endif; ?>
