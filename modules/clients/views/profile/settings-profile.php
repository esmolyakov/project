<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use app\helpers\FormatHelpers;
    use app\modules\clients\widgets\SubMenuProfile;
    use app\modules\clients\widgets\AlertsShow;
    
/* 
 * Настройки профиля
 */

$this->title = 'Настройки';
?>


<div class="col-12 p-0 m-0 text-center profile-settings">
    <div class="row">
        <div class="col-3 profile-info">
            <h5 class="profile-title">Профиль</h5>
            <?= $this->render('data/profile-info', ['user_info' => $user_info]) ?>
        </div>
        
        <div class="col-9 profile-form-settings">
            <h5 class="profile-title-setting">Изменить пароль</h5>
            <?= $this->render('_form/change-password', [
                    'model_password' => $model_password,
                    'sms_model' => $sms_model,
                    'is_change_password' => $is_change_password]) ?>
            
            <h5 class="profile-title-setting">Изменить номер мобильного телефона</h5>
            <?php // = $this->render('_form/change-password', ['model_password' => $model_password]) ?>
            
            <h5 class="profile-title-setting">Изменить адрес электронноый почты</h5>
            <?php // = $this->render('_form/change-email', ['user' => $user]) ?>
            
        </div>
    </div>
</div>


<?php /*
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <?php // = SubMenuProfile::widget() ?>
    <?php // = AlertsShow::widget() ?>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Общая информация о профиле</strong>
            </div>
            <div class="panel-body">
                
                <div class="text-center">
                    <?= Html::img($user_info->photo, ['id' => 'photoPreview','class' => 'img-circle', 'alt' => $user_info->username, 'width' => 150]) ?>
                </div>

                <hr />
                <div class="col-md-12">
                    <p>
                        Фамилия имя отчество: 
                        <?= 
                            $user_info->surname . ' '. $user_info->name . ' ' . $user_info->secondName ?>
                    </p>
                    <p>Роль: <?= $user_info->role ?></p>
                    <p>Логин: <?= $user_info->username ?></p>
                    <p>Дата регистрации: <?= FormatHelpers::formatDate($user_info->dateRegister) ?></p>
                    <p>Дата последнего входа на портал: <?= FormatHelpers::formatDate($user_info->lastLogin) ?></p>
                    <p>Статус: <?= $user_info->getStatus() ?></p>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Изменить пароль</strong>
            </div>
            <div class="panel-body">
                <?php
                    $form_psw = ActiveForm::begin([
                        'id' => 'change-password-form',
                        'validateOnSubmit' => true,
                        'validateOnBlur' => false,
                        'validateOnChange' => false,
                    ]);
                ?>
                
                    <?= $form_psw->field($model_password, 'current_password')
                            ->input('password', [
                                'placeHolder' => $model_password->getAttributeLabel('current_password'),
                                'class' => 'form-control show_password'
                            ]) 
                    ?>
                
                    <?= $form_psw->field($model_password, 'new_password')
                            ->input('password', [
                                'placeHolder' => $model_password->getAttributeLabel('new_password'),
                                'class' => 'form-control show_password'])
                    ?>
                
                    <?= $form_psw->field($model_password, 'new_password_repeat')
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
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Изменить адрес электронной почты и/или мобильный телефон</strong>
            </div>
            <div class="panel-body">
                <p>
                    Внимание, на указанные номер мобильного телефона и электронную почту будут приходить оповещения с портала. 
                    Отключить оповещения вы можете в разделе <?= Html::a('Профиль', ['profile/index']) ?>.
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
                
                    <?= $form_email->field($user, 'user_mobile')
                        ->widget(MaskedInput::className(), [
                            'mask' => '+7 (999) 999-99-99'])
                        ->input('text', [
                            'placeHolder' => $user->getAttributeLabel('user_mobile')])
                        ->label() 
                    ?>
                
                    <div class="form-group text-right">
                        <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
                    </div>
                
                <?php ActiveForm::end() ?>
            </div>
        </div>       
        
    </div>

</div>
 * */ ?>
