<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
    use kartik\date\DatePicker;

/* 
 * Форма
 * 
 * Новый сотрудник
 */
?>
<div class="dispatchers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'add-dispatcher',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnChange' => true,
            'options' => [
                'enctype' => 'multipart/form-data',
            ],            
        ]);
    ?>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Информация о сотруднике</div>
                <div class="panel-body">
                    
                    <?= $form->field($model, 'surname')
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('surname'),])
                            ->label() ?>
                    
                    <?= $form->field($model, 'name')
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('name'),])
                            ->label() ?>
                    
                    <?= $form->field($model, 'second_name')
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('second_name'),])
                            ->label() ?>
                    
                    <?= $form->field($model, 'birthday')
                            ->widget(DatePicker::className(), [
                                'language' => 'ru',
                                'options' => [
                                    'placeHolder' => 'Дата рождения',
                                ],
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                'pluginOptions' => [
                                    'autoClose' => true,
                                    'format' => 'yyyy-mm-dd',
                                ],
                            ]) ?>
                    
                    <?= $form->field($model, 'mobile')
                            ->widget(MaskedInput::className(), [
                                'mask' => '+7(999) 999-99-99'])
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('mobile'),])
                            ->label() ?>                    
                    
                    <?= $form->field($model, 'department')
                            ->dropDownList($department_list, [
                                'class' => 'form-control department_list',
                                'prompt' => 'Выберите подразделение из списка...',])
                            ->label() ?>

                    <?= $form->field($model, 'post')
                            ->dropDownList($post_list, [
                                'prompt' => 'Выберите должность из списка...',
                                'class' => 'form-control posts_list',])
                            ->label() ?>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Фотография</div>
                <div class="panel-body">
                    <div class="text-center">
                        <?= Html::img($model->photo, ['id' => 'photoPreview', 'class' => 'img-circle', 'alt' => $model->username, 'width' => 150]) ?>
                        <br />
                        <?= $form->field($model, 'photo')->input('file', ['id' => 'btnLoad'])->label(false) ?>
                    </div>
                    <?= $form->field($model, 'role')
                            ->dropDownList($roles, [
                                'prompt' => 'Выберите роль из списка...',
                                'disabled' => false,])
                            ->label() ?>
                    
                    <?= $form->field($model, 'is_new')->checkbox() ?>
                    
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Информация о пользователе</div>
                <div class="panel-body">
                    
                    <?= $form->field($model, 'username')
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('username'),])
                            ->label() ?>
                    
                    <?= $form->field($model, 'email')
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('email'),])
                            ->label() ?>
                    
                    <?= $form->field($model, 'password')
                            ->input('password', [
                                'placeHolder' => $model->getAttributeLabel('password'),
                                'class' => 'form-control show_password',])
                            ->label() ?>
                    
                    <?= $form->field($model, 'password_repeat')
                            ->input('password', [
                                'placeHolder' => $model->getAttributeLabel('password_repeat'),
                                'class' => 'form-control show_password',])
                            ->label() ?>                    
                    
                    <?= Html::checkbox('show_password_ch', false) ?> <span class="show_password__text">Показать пароль</span>
                    
                </div>
            </div>
        </div>
    
        <div class="col-md-12 text-right">
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
        </div>
    
    <?php ActiveForm::end() ?>
    
</div>