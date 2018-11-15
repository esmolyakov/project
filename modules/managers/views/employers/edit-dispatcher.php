<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use kartik\date\DatePicker;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/*
 * Форма
 * 
 * Редактирование профиля сотрудника
 */
$this->title = $dispatcher_info->fullName . '<span class="badge">' . $role . '</span>';
?>
<div class="dispatchers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= AlertsShow::widget() ?>
    
    <hr />
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'edit-dispatcher',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnChange' => true,
            'options' => [
                'enctype' => 'multipart/form-data',
            ],            
        ]);
    ?>
    <div class="col-md-4">
        <div class="text-center">
            <?= Html::img($user_info->photo, ['id' => 'photoPreview','class' => 'img-circle', 'alt' => $user_info->user_login, 'width' => 150]) ?>
            <br />
            <?= $form->field($user_info, 'user_photo')->input('file', ['id' => 'btnLoad'])->label(false) ?>
        </div>
        <hr />
        <p>Логин: <?= $user_info->user_login ?></p>
        <p>Дата регистрации: <?= FormatHelpers::formatDate($user_info->created_at) ?></p>
        <p>Дата последнего логина: <?= FormatHelpers::formatDate($user_info->last_login) ?></p>
        <p>Статус: <?= $user_info->userStatus ?> </p>
        
        <?= $form->field($user_info, 'role')
                ->dropDownList($roles, [
                    'value' => $name_role,
                    'disabled' => false,])
                ->label() ?>
        
        <?php if ($user_info->status == 1) : ?>
            <?= Html::button('Заблокировать', [
                    'class' => 'btn btn-danger btn-sm block_user',
                    'data-user' => $user_info->user_id,
                    'data-status' => 2]) 
            ?>
        <?php elseif ($user_info->status == 2)  : ?>
            <?= Html::button('Разблокировать', [
                    'class' => 'btn btn-success btn-sm block_user',
                    'data-user' => $user_info->user_id,
                    'data-status' => 1]) 
            ?>
        <?php endif; ?>
        
        <?= Html::button('Смена пароля', ['class' => 'btn btn-primary btn-sm']) ?>
    </div>
    
    <div class="col-md-8">
        <h3>Профиль</h3>
        <div class="col-md-6">
            <?= $form->field($dispatcher_info, 'employers_surname')
                    ->input('text', [
                        'placeHolder' => $dispatcher_info->getAttributeLabel('employers_surname')])
                    ->label() ?>

            <?= $form->field($dispatcher_info, 'employers_name')
                    ->input('text', [
                        'placeHolder' => $dispatcher_info->getAttributeLabel('employers_surname')])
                    ->label() ?>

            <?= $form->field($dispatcher_info, 'employers_second_name')
                    ->input('text', [
                        'placeHolder' => $dispatcher_info->getAttributeLabel('employers_surname')])
                    ->label() ?>
                    
            <?= $form->field($dispatcher_info, 'employers_birthday')
                    ->widget(DatePicker::className(), [
                        'language' => 'ru',
                        'options' => [
                            'placeholder' => 'Дата рождения',
                        ],
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]) ?>
        </div>
        
        <div class="col-md-6">
            
            <?= $form->field($dispatcher_info, 'employers_department_id')
                    ->dropDownList($department_list, [
                        'class' => 'form-control department_list',
                        'prompt' => 'Выберите подразделение из списка...',])
                    ->label() ?>
                    
            <?= $form->field($dispatcher_info, 'employers_posts_id')
                    ->dropDownList($post_list, [
                        'class' => 'form-control posts_list',])
                    ->label() ?>
                    
        </div>
        
        <div class="col-md-12 text-right">
            <?= Html::button('Удалить', [
                'class' => 'btn btn-danger delete_dispatcher',
                'data-target' => '#delete_disp_manager',
                'data-toggle' => 'modal',
                'data-employer' => $dispatcher_info->id,
                'data-full-name' => $dispatcher_info->fullName,]) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
        
    </div>
    <?php ActiveForm::end() ?>
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_dispatcher']) ?>