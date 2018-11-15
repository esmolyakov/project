<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Настройки профиля Администратора
 */

$this->title = 'Настройки профиля';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= AlertsShow::widget() ?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'edit-profile-manager',
        ]);
    ?>
        <?= $form->field($model_password, 'current_password')
                ->input('password', [
                    'placeHolder' => $model_password->getAttributeLabel('current_password'),
                    'class' => 'form-control show_password'])
        ?>
                
        <?= $form->field($model_password, 'new_password')
                ->input('password', [
                    'placeHolder' => $model_password->getAttributeLabel('new_password'),
                    'class' => 'form-control show_password'])
        ?>
                
        <?= $form->field($model_password, 'new_password_repeat')
                ->input('password', [
                    'placeHolder' => $model_password->getAttributeLabel('new_password_repeat'),
                    'class' => 'form-control show_password']) 
        ?>
    
        <div class="form-group">
            <?= Html::checkbox('show_password_ch', false) ?> <span class="show_password__text">Показать пароли</span>
        </div>
    
        <div class="form-group text-right">
            <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
        </div>
                    
    
    <?php ActiveForm::end(); ?>
    
</div>