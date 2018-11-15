<?php
    
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
    use yii\bootstrap4\Modal;
    use app\modules\clients\widgets\SubMenuProfile;
    use app\modules\clients\widgets\AlertsShow;
    use app\modules\clients\widgets\ModalWindows;
    
/*
 * Профиль пользователя
 */
$this->title = 'Профиль собственника';
?>

<?php //= SubMenuProfile::widget() ?>
<?php // = AlertsShow::widget() ?>


<?php
    $form = ActiveForm::begin([
        'id' => 'profile-form',
        'action' => ['profile/update-profile'],
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'border-container d-block m-0 p-0 border-container',
        ],
    ])
?>

<div class="col-12 p-0 m-0 text-center profile-bg">
    <div class="face-container">
        <?= Html::img($user_info->photo, [
                'class' => 'rounded-circle mx-auto face',
                'id' => 'photoPreview',
                'alt' => $user_info->username,]
            ) ?>
    </div>
    <div class="profile-name mx-auto">
        <h5 class="profile-name-h text center ">
            <?= $user_info->getFullNameClient(true) ?>
        </h5>
    </div>
    <div class="profile-upload">
        <?= $form->field($user, 'user_photo', ['template' => '<label class="text-center btn btn-upload" role="button">{input}{label}{error}'])
                ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])->label('Загрузить фото') ?>
    </div>   
</div>
    
<div class="col-12 p-0 m-0 text-center material ">
    <div class="account-select mx-auto">
        <div class="chip-label">
            <span class="badge badge-darkblue-personal-account">Лицевой счет</span>
        </div>
        <span class="account-dropdown">
            <?= Html::dropDownList('_list-account', $this->context->_choosing, $accounts_list, [
                    'placeholder' => '',
                    'id' => '_list-account',
                    'data-client' => $user_info->clientID]) 
            ?>
        </span>
    </div>
    
    
    
    <div class="col-12 mx-0 row personal-info">
        
        <!--Собственник-->
        <div class="col-6 clients-profile-info">
            <h5 class="profile-title">
                Мои контактные данные&nbsp;
                <?= Html::a('<i class="fa fa-edit"></i>', ['profile/settings-profile']) ?>
            </h5>
            
            <div class="field">
                <label for="user_mobile" class="field-label"><i class="fa fa-mobile"></i> Мобильный телефон</label>
                <?= Html::input('text', 'user_mobile', $user_info->mobile, ['class' => 'field-input']) ?>
            </div>
            
            <div class="field">
                <label for="user_mobile" class="field-label"><i class="fa fa-phone"></i> Домашний телефон</label>
                <?= Html::input('text', 'user_mobile', $user_info->otherPhone, ['class' => 'field-input']) ?>
            </div>
            
            <div class="field">
                <label for="user_email" class="field-label"><i class="fa fa-envelope-o"></i> Электронная почта</label>
                <?= Html::input('text', 'user_email', $user_info->email, ['class' => 'field-input']) ?>
            </div>
            
        </div>
                

        <!--Арендатор-->
        
        <div class="col-6 rent-profile-info">
            <h5 class="profile-title-rent">
                <label class="el-switch">
                    <?= Html::checkbox('is_rent', $is_rent ? 'checked' : '', ['id' => 'is_rent']) ?>
                    <span class="el-switch-style"></span>
                    <span class="margin-r">Арендатор</span>
                </label>
                    
            </h5>
            
            <div id="content-replace" class="form-add-rent">
            <?php if (isset($is_rent) && $is_rent) : ?>
                <?= $this->render('_form/rent-view', [
                        'form' => $form,
                        'model_rent' => $model_rent]) ?>
            <?php endif; ?>
            </div>      
        </div>
                
    </div>
           
    <div class="col-12 spam-agree-txt">
        
        <?= $form->field($user, 'user_check_email', ['template' => '{input}{label}'])->checkbox([], false)->label() ?> 

        <div class="save-btn-group mx-auto">
            <div class="text-center">
                <?= Html::submitButton('Сохранить изменения', ['class' => 'btn blue-btn']) ?>
            </div>
        </div>

    </div>            

<?php ActiveForm::end(); ?>
</div>

<?php if (!$is_rent) : ?>
    <?= $this->render('_form/create_rent', ['add_rent' => $add_rent]) ?>
<?php endif; ?>

<?= ModalWindows::widget(['modal_view' => 'changes_rent']) ?>