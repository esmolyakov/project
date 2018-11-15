<?php

    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\helpers\Html;

/* 
 * Создание заявки
 */

?>
<?php
    $form = ActiveForm::begin([
        'id' => 'create-new-request',
    ])
?>

<?= $form->field($model, 'category_service')
        ->dropDownList($service_categories, [
            'prompt' => 'Выбрать из списка...'])
        ->label() ?>

<?= $form->field($model, 'service_name')
        ->dropDownList($service_categories)
        ->label() ?>

<?= $form->field($model, 'phone')
        ->widget(MaskedInput::className(), [
            'mask' => '+7 (999) 999-99-99'])
        ->input('text', [
            'placeHolder' => $model->getAttributeLabel('phone')])
        ->label() ?>

<?= $form->field($model, 'fullname')
        ->input('text', [
            'placeHolder' => $model->getAttributeLabel('fullname')])
        ->label() ?>

<?= $form->field($model, 'description')->textarea()->label() ?>

<?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end() ?>