<?php
    
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
    
/* 
 * Форма добавить арендатора
 */

?>

    <?= $form->field($add_rent, 'client_id', ['options' => ['class' => 'hidden']])
        ->hiddenInput([
            'value' => $client_id,
            'class' => 'hidden',
        ])->label(false) ?>

    <?= $form->field($add_rent, 'rents_surname')
            ->input('text', [
                'placeHolder' => $add_rent->getAttributeLabel('rents_surname'),
                'class' => 'form-control rents-surname'])
            ->label() ?>

    <?= $form->field($add_rent, 'rents_name')
            ->input('text', [
                'placeHolder' => $add_rent->getAttributeLabel('rents_name'),
                'class' => 'form-control rents-name'])
            ->label() ?>

    <?= $form->field($add_rent, 'rents_second_name')
            ->input('text', [
                'placeHolder' => $add_rent->getAttributeLabel('rents_second_name'),
                'class' => 'form-control rents-second-name'])
            ->label() ?>

    <?= $form->field($add_rent, 'rents_mobile')
            ->widget(MaskedInput::className(), [
                'mask' => '+7(999) 999-99-99'])
            ->input('text', [
                'placeHolder' => $add_rent->getAttributeLabel('rents_mobile'),
                'class' => 'form-control rents-mobile'])
            ->label() ?>

    <?= $form->field($add_rent, 'rents_email')
            ->input('text', [
                'placeHolder' => $add_rent->getAttributeLabel('rents_email'),
                'class' => 'form-control rents-email'])
            ->label() ?>

    <?= $form->field($add_rent, 'password')
            ->input('password', [
                'placeHolder' => $add_rent->getAttributeLabel('password'),
                'class' => 'form-control rents-hash show_password'])
            ->label() ?>                                   

    <?= Html::checkbox('show_password_ch', false) ?> <span class="show_password__text">Показать пароль</span>
    