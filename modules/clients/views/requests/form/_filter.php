<?php

    use yii\widgets\ActiveForm;
?>

<div class="request-search">
    <?php $form = ActiveForm::begin([
        'id' => 'filter-form',
        'options' => ['data-pjax' => true],
    ]); ?>

    <?= $form->field($model_filter, '_value')
            ->dropDownList($type_requests, [
                'id' => 'account_number',
                'prompt' => 'Все заявки'])
            ->label('Вид заявки') ?>

    <?php ActiveForm::end(); ?>
</div>