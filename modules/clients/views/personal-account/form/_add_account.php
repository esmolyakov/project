<?php

    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\modules\clients\widgets\AlertsShow;


/* 
 * Форма создание лицевого счета
 */
$this->title = 'Добавить лицевой счет';
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>

     <?= AlertsShow::widget() ?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'add-account',
            'action' => [
                'personal-account/add-record-account', 'form' => 'add-account',
            ],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnChange' => true,
        ])
    ?>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Информация нового лицевого счета</div>
            <div class="panel-body">
                    
                <?= $form->field($add_account, 'account_number')
                        ->input('text', [
                            'placeHolder' => $add_account->getAttributeLabel('account_number'),
                            'class' => 'form-control number'])
                        ->label() ?>
                    
                <?= $form->field($add_account, 'account_last_sum')
                        ->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' => 'decimal',
                                'digits' => 2,
                                'digitsOptional' => false,
                                'radixPoint' => ',',
                                'groupSeparator' => ' ',
                                'autoGroup' => true,
                                'removeMaskOnSubmit' => true,
                            ]])
                        ->input('text', [
                            'placeHolder' => '0,00', 
                            'class' => 'form-control'])
                        ->label() ?>
                    
                    
                <?= $form->field($add_account, 'square_flat')
                        ->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' => 'decimal',
                                'digits' => 0,
                                'groupSeparator' => ' ',
                                'autoGroup' => true,
                            ]])
                        ->input('text', [
                            'class' => 'form-control']) ?>
                    
                <?= $form->field($add_account, 'account_organization_id')
                        ->dropDownList($all_organizations, [
                            'prompt' => 'Выбрать организацию из списка...',
                            'class' => 'form-control']) ?>
                    
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Собственник</div>
            <div class="panel-body">
                <?= $form->field($add_account, 'account_client_surname')
                        ->input('text', [
                            'value' => $user_info->surname,
                            'disabled' => true,
                            'class' => 'form-control',])
                        ->label() ?>

                <?= $form->field($add_account, 'account_client_name')
                        ->input('text', [
                            'value' => $user_info->name,
                            'disabled' => true,
                            'class' => 'form-control'])
                        ->label() ?>

                <?= $form->field($add_account, 'account_client_secondname')
                        ->input('text', [
                            'value' => $user_info->secondName,
                            'disabled' => true,
                            'class' => 'form-control'])
                        ->label() ?>

                <?= $form->field($add_account, 'flat')
                        ->dropDownList($all_flat, [
                            'prompt' => 'Выбрать адрес из списка...',
                            'class' => 'form-control']) ?>
                
            </div>
        </div>
    </div>



    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Арендатор</div>
            <div class="panel-body">
                   
                <?= $form->field($add_account, 'is_rent')
                        ->checkbox([
                            'id' => 'isRent']) ?>
                    
                Добавить нового арендатора и создать для него новую учетную запись для входа на портал
                    
                <?= Html::button('Добавить арендатора', [
                        'class' => 'btn btn-primary btn-sm btn__add-rent', 
                        'data-toggle' => 'modal', 
                        'data-target' => '#add-rent-modal']) ?>
                    
                    
                <div id="add-rent-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                               <button type="button" class="close btn__modal_close" data-dismiss="modal">&times;</button>
                               <h4 class="modal-title">Новый арендатор</h4>
                            </div>
                            <div class="modal-body">
                                   
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
                                   
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success btn__add_rent">Добавить</button>
                                <button type="button" class="btn btn-default btn__modal_rent_close" data-dismiss="modal">Отмена</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
            
    </div>
                        
    <div class="col-md-12">
        <div class="modal-footer">
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-danger']) ?>
            <?= Html::submitButton('Отмена', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
            <?php ActiveForm::end() ?>
        </div>
    </div>

</div>

<?php
$this->registerJs('
    $("#add-rent-modal .btn__add_rent").on("click", function(e) {
        e.preventDefault();
        
        var rentSurname = $("#add-rent-modal .rents-surname").val();
        var rentName = $("#add-rent-modal .rents-name").val();
        var rentSecondName = $("#add-rent-modal .rents-second-name").val();
        var rentMobile = $("#add-rent-modal .rents-mobile").val();
        var rentsEmail = $("#add-rent-modal .rents-email").val();
        var rentsHash = $("#add-rent-modal .rents-hash").val();

        $.ajax({
            url: "' . Url::to(['personal-account/validate-add-rent-form']) . '",
            method: "POST",
            data: {
                "' . Html::getInputName($add_rent, 'rents_surname') . '": rentSurname,
                "' . Html::getInputName($add_rent, 'rents_name') . '": rentName,
                "' . Html::getInputName($add_rent, 'rents_second_name') . '": rentSecondName,
                "' . Html::getInputName($add_rent, 'rents_mobile') . '": rentMobile,
                "' . Html::getInputName($add_rent, 'rents_email') . '": rentsEmail,
                "' . Html::getInputName($add_rent, 'password') . '": rentsHash,
            },
            success: function(response) {
                console.log(response.status);
                console.log(response.errors);
                if (response.status == true) {
                    // console.log(rentSurname + " " + rentName + " " + rentSecondName + " " + rentMobile + " " + rentsEmail + " " + rentsHash);
                    $("#add-rent-modal").modal("hide");
                } else {
                    if (typeof response.errors != "undefined") {
                        var errors = response.errors;
                        
                        var parentContainer = $("#add-rent-modal .rents-surname").parent().parent();
                        if (errors.rents_surname) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find(".help-block").text(errors.rents_surname);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                        
                        var parentContainer = $("#add-rent-modal .rents-name").parent();
                        if (errors.rents_name) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find(".help-block").text(errors.rents_name);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                        
                        var parentContainer = $("#add-rent-modal .rents-second-name").parent();
                        if (errors.rents_second_name) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find("help-block").text(errors.rents_second_name);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                        
                        var parentContainer = $("#add-rent-modal .rents-mobile").parent();
                        if (errors.rents_mobile) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find(".help-block").text(errors.rents_mobile);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                        
                        var parentContainer = $("#add-rent-modal .rents-email").parent();
                        if (errors.rents_email) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find(".help-block").text(errors.rents_email);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                        
                        var parentContainer = $("#add-rent-modal .rents-hash").parent();
                        if (errors.password) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find(".help-block").text(errors.password);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                    }
                }
            },
        });
    });
')
?>