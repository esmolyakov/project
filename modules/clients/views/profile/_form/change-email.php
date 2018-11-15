<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Смена электронного адреса
 */
?>
<p>
    Внимание, на указанный адрес электронной почты будут приходить оповещения с портала. 
    Отключить оповещения вы можете на странице вашего профиля <?= Html::a('Профиль', ['profile/index']) ?>.
</p>
<?php
    $form_email = ActiveForm::begin([
        'id' => 'change-email-form',
        'validateOnSubmit' => true,
        'validateOnBlur' => false,
        'validateOnChange' => false,
    ]);
?>

    <?= $form_email->field($user, 'user_email')->input('text')->label() ?>
    <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
                
<?php ActiveForm::end() ?>
