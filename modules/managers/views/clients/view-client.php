<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Просмотр инофрмации о Собственнике
 */
$this->title = 'Собственник ' . $client_info->fullName;
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= AlertsShow::widget() ?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'view-client-info',
            'method' => 'POST',
            'validateOnChange' => false,
            'validateOnBlur' => false,
            'options' => [
                'enctype' => 'multipart/form-data',
                'data-pjax' => true,
            ],
        ]);
    ?>
    
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Контактные данные                
            </div>
            <div class="panel-body">
                
                <?= $form->field($client_info, 'clients_surname')
                        ->input('text', [
                            'placeHolder' => $client_info->getAttributeLabel('clients_surname')])
                        ->label() ?>
                
                <?= $form->field($client_info, 'clients_name')
                        ->input('text', [
                            'placeHolder' => $client_info->getAttributeLabel('clients_name')])
                        ->label() ?>
                
                <?= $form->field($client_info, 'clients_second_name')
                        ->input('text', [
                            'placeHolder' => $client_info->getAttributeLabel('clients_second_name')])
                        ->label() ?>
                
                <?= $form->field($client_info, 'clients_mobile')
                        ->widget(MaskedInput::className(), [
                            'mask' => '+7 (999) 999-99-99'])
                        ->input('text', [
                            'placeHolder' => $client_info->getAttributeLabel('clients_mobile')])
                        ->label() ?>
                
                <?= $form->field($client_info, 'clients_phone')
                        ->widget(MaskedInput::className(), [
                            'mask' => '+7 (999) 999-99-99'])            
                        ->input('text', [
                            'placeHolder' => $client_info->getAttributeLabel('clients_phone')])
                        ->label() ?>
                
                <?= $form->field($user_info, 'user_email')
                        ->input('text', [
                            'placeHolder' => $user_info->getAttributeLabel('user_email')])
                        ->label() ?>
                
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Информация о пользователе</div>
            <div class="panel-body">
                <div class="text-center">
                    <?php if ($user_info->user_photo) : ?>
                        <?= Html::img($user_info->user_photo, [
                                'id' => 'photoPreview',
                                'class' => 'img-circle', 
                                'alt' => $user_info->user_login, 
                                'width' => 150]) ?>
                    <?php else: ?>
                        <?= Html::img('@web/images/no-avatar.jpg', [
                                'id' => 'photoPreview',
                                'class' => 'img-circle', 
                                'alt' => $user_info->user_login, 
                                'width' => 150]) ?>
                    <?php endif; ?>
                </div>
                
                <?= $form->field($user_info, 'user_photo')->input('file', ['id' => 'btnLoad']) ?>
                <?= $form->field($user_info, 'user_check_email')->checkbox() ?>
                
                <?php if ($user_info->status == 1) : ?>
                    <?= Html::button('Заблокировать', [
                            'class' => 'btn btn-danger block_user',
                            'data-user' => $user_info->user_id,
                            'data-status' => 2]) 
                    ?>
                <?php elseif ($user_info->status == 2)  : ?>
                    <?= Html::button('Разблокировать', [
                            'class' => 'btn btn-success block_user',
                            'data-user' => $user_info->user_id,
                            'data-status' => 1]) 
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        
        <?= $form->field($add_rent, 'account_id')->dropDownList($list_account, [
                'value' => $account_choosing,
                'id' => '_list-account',
                'class' => 'form-control',
                'data-client' => $client_info->clients_id]) ?>
        
        <div class="panel panel-default">
            <div class="panel-heading">Данные об арендаторе</div>
            <div class="panel-body">
                <?php if (isset($is_rent)) : ?>
                    <?= Html::checkbox('is_rent', $is_rent ? 'checked' : '', ['id' => 'is_rent']) ?> Арендатор
                <?php endif; ?>
                
                <div id="content-replace" class="form-add-rent">
                    <?php if (isset($is_rent) && $is_rent) : ?>
                        <?= $this->render('_form/rent-view', [
                                'form' => $form,
                                'rent_info' => $rent_info]) 
                        ?>
                    <?php else : ?>
                        <p>Арендатор отсутствует</p>
                        <?php /* = $this->render('_form/add-rent', [
                            'form' => $form, 
                            'add_rent' => $add_rent, 
                            'client_id' => $client_info->id]) */ ?>
                    <?php endif; ?>
                </div>
                    
            </div>
        </div>
    </div>
    
    <div class="col-md-12 text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
        
    <?php ActiveForm::end() ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_rent']) ?>