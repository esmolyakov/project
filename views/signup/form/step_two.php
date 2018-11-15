<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
/* 
 * Регистрация, шаг 2
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'signup-form-step-two',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => '<div class="field">{label}{input}</div>',
            'labelOptions' => ['class' => 'label-registration hidden'],
        ],
        'options' => [
            'class' => 'form-signin d-block my-auto material',
        ],
    ])
?>

<?= $form->errorSummary($model_step_two); ?>

<?= $form->field($model_step_two, 'email')
        ->input('text', ['class' => 'field-input'])
        ->label($model_step_two->getAttributeLabel('email'), ['class' => 'field-label']) ?>
                
<?= $form->field($model_step_two, 'password')
        ->input('password', ['class' => 'field-input'])
        ->label($model_step_two->getAttributeLabel('password'), ['class' => 'field-label']) ?>
        
<?= $form->field($model_step_two, 'password_repeat')
        ->input('password', ['class' => 'field-input'])
        ->label($model_step_two->getAttributeLabel('password_repeat'), ['class' => 'field-label']) ?>
                
<div class="text-center circle-btn-block mx-auto">
    <?= Html::submitButton('', ['class' => 'blue-circle-btn']) ?>    
</div>

<?php ActiveForm::end(); ?>