<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Форма добавление характеристики выбранному дому
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'add-characteristic',
        'enableAjaxValidation' => true,
        'validationUrl' => ['edit-description-validate', 'form' => 'add-characteristic'],
    ]);
?>

    <?= $form->field($model, 'characteristics_house_id')->hiddenInput([
            'value' => $house_id,
            'class' => 'hidden',])
        ->label(false) ?>

    <?= $form->field($model, 'characteristics_name')->input('text')->label() ?>

    <?= $form->field($model, 'characteristics_value')->input('text')->label() ?>

    <?= Html::button('Отмена', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end() ?>