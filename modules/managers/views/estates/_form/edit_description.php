<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Форма редактировния описания дома
 */
?>
<h4>
    <?= 'Ул. ' . $model->houses_street . ', ' . $model->houses_number_house ?> 
</h4>
<?php
    $form = ActiveForm::begin([
        'id' => 'edit-form-description',
        'enableAjaxValidation' => true,
        'validationUrl' => ['edit-description-validate', 'form' => 'edit-form-description'],
    ]);
?>
    <?= $form->field($model, 'houses_description')->textarea()->label() ?>

    <?= Html::submitButton('Сохранить', [
            'class' => 'btn btn-success',
        ]) ?>

    <?= Html::button('Отмена', [
            'data-dismiss' => 'modal',
            'class' => 'btn btn-primary',
        ]) ?>

<?php ActiveForm::end(); ?>
