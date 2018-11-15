<?php

    use yii\widgets\MaskedInput;
    
/* 
 * Форма Арендатор
 */

?>
hhhh
<?php if ($model_rent) : ?>

    <?= $form->field($model_rent, 'rents_id', ['options' => ['class' => 'hidden']])->hiddenInput(['value' => $model_rent->rents_id, 'id' => '_rents'])->label(false) ?>
    
    <?= $form->field($model_rent, 'rents_surname', ['template' => '<div class="field has-label"></i>{label}{input}{error}</div>'])
            ->input('text', ['class' => 'field-input'])
            ->label('<i class="fa fa-user-o"></i> ' . $model_rent->getAttributeLabel('rents_surname'), ['class' => 'field-label']) ?>
    
    <?= $form->field($model_rent, 'rents_name', ['template' => '<div class="field has-label">{label}{input}{error}</div>'])
            ->input('text', ['class' => 'field-input'])
            ->label('<i class="fa fa-user-o"></i> ' . $model_rent->getAttributeLabel('rents_name'), ['class' => 'field-label']) ?>        

    <?= $form->field($model_rent, 'rents_second_name', ['template' => '<div class="field has-label">{label}{input}{error}</div>'])
            ->input('text', ['class' => 'field-input'])
            ->label('<i class="fa fa-user-o"></i> ' . $model_rent->getAttributeLabel('rents_second_name'), ['class' => 'field-label']) ?>

    <?= $form->field($model_rent, 'rents_mobile', ['template' => '<div class="field has-label">{label}{input}{error}</div>'])
            ->widget(MaskedInput::className(), [
                'mask' => '+7 (999) 999-99-99'])
            ->input('text', ['class' => 'field-input'])
            ->label('<i class="fa fa-mobile"></i> ' . $model_rent->getAttributeLabel('rents_mobile'), ['class' => 'field-label']) ?>

    <?= $form->field($model_rent, 'rents_mobile_more', ['template' => '<div class="field has-label">{label}{input}{error}</div>'])
            ->widget(MaskedInput::className(), [
                'mask' => '+7 (9999) 999-99-99'])
            ->input('text', ['class' => 'field-input'])
            ->label('<i class="fa fa-phone"></i> ' . $model_rent->getAttributeLabel('rents_mobile_more'), ['class' => 'field-label']) ?>

<?php endif; ?>
