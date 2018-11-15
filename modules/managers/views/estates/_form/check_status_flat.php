<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Установка статуса "Должник"
 * Добавление примечания к установке статуса
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'form-add-note',
        'enableAjaxValidation' => true,
        'validationUrl' => ['edit-description-validate', 'form' => 'form-add-note'],
    ]);
?>

    <?= $form->field($model, 'notes_flat_id')->hiddenInput(['value' => $flat_id, 'class' => 'hidden'])->label(false) ?>

    <?= $form->field($model, 'notes_name')->textarea()->label() ?>

    <?= Html::button('Отмена', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('Сохранение изменений', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>