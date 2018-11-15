<?php

    use yii\widgets\MaskedInput;

/* 
 * Данные арендатора
 */
?>
<?php if (isset($rent_info) && $rent_info) : ?>

    <?= $form->field($rent_info, 'rents_id', ['options' => ['class' => 'hidden']])
            ->hiddenInput([
                'value' => $model_rent->rents_id, 
                'id' => '_rents'])
            ->label(false) ?>

    <?= $form->field($rent_info, 'rents_surname')
            ->input('text', [
                'id' => 'rents_surname',
                'placeHolder' => $rent_info->getAttributeLabel('rents_surname'),
                'data-surname' => $rent_info->rents_surname,])
            ->label() ?>

    <?= $form->field($rent_info, 'rents_name')
            ->input('text', [
                'id' => 'rents_name',
                'placeHolder' => $rent_info->getAttributeLabel('rents_name'),
                'data-name' => $rent_info->rents_name])
            ->label() ?>

    <?= $form->field($rent_info, 'rents_second_name')
            ->input('text', [
                'id' => 'rents_second_name',            
                'placeHolder' => $rent_info->getAttributeLabel('rents_second_name'),
                'data-second-name' => $rent_info->rents_second_name])
            ->label() ?>

    <?= $form->field($rent_info, 'rents_mobile')
            ->widget(MaskedInput::className(), [
                'mask' => '+7 (999) 999-99-99'])
            ->input('text', [
                'placeHolder' => $rent_info->getAttributeLabel('rents_mobile')])
            ->label() ?>

    <?= $form->field($rent_info, 'rents_mobile_more')
            ->widget(MaskedInput::className(), [
                'mask' => '+7 (999) 999-99-99'])
            ->input('text', [
                'placeHolder' => $rent_info->getAttributeLabel('rents_mobile_more')])
            ->label() ?>

<?php endif; ?>