<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
/* 
 * Форма регистрации, шаг первый
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'signup-form-step-one',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => '<div class="field">{label}{input}</div>',
            'labelOptions' => ['class' => 'label-registration hidden'],
        ],
        'options' => [
            'class' => 'form-signin d-block my-auto',
        ],
    ])
?>

<?= $form->errorSummary($model_step_one); ?>

<?= $form->field($model_step_one, 'account_number')
        ->input('text', ['class' => 'field-input'])
        ->label($model_step_one->getAttributeLabel('account_number'), ['class' => 'field-label']) ?>
                
<?= $form->field($model_step_one, 'last_summ')
        ->input('input', ['class' => 'field-input'])
        ->label($model_step_one->getAttributeLabel('last_summ'), ['class' => 'field-label']) ?>
        
<?= $form->field($model_step_one, 'square')
        ->input('text', ['class' => 'field-input'])
        ->label($model_step_one->getAttributeLabel('square'), ['class' => 'field-label']) ?>

<div class="text-center circle-btn-block mx-auto">
    <?= Html::submitButton('', ['class' => 'blue-circle-btn']) ?>    
</div>

<?php ActiveForm::end(); ?>