<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Редактирование услуги
 */
$this->title = $service->services_name;
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'create-service',
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]);
    ?>
    
    <div class="col-md-2">
        <div class="text-center">
            <?= Html::img($service->image, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => $service->services_name, 'width' => 150]) ?>
        </div>
        <br />
        <?= $form->field($service, 'services_image')
                ->input('file', ['id' => 'btnLoad'])
                ->label(false) ?>
    </div>
    
    <div class="col-md-5">
        <?= $form->field($service, 'isType')
                ->radioList($service_types)->label(false) ?>
        
        <?= $form->field($service, 'services_name')
                ->input('text', [
                    'placeHolder' => $service->getAttributeLabel('services_name'),]) ?>
        
        <?= $form->field($service, 'services_category_id')
                ->dropDownList($service_categories, [
                    'prompt' => 'Выберите услугу из списка...',]) ?>
        
        <?= $form->field($service, 'services_unit_id')
                ->dropDownList($units, [
                    'prompt' => 'Выбрать из списка...']) ?>
        
        <?= $form->field($service, 'services_cost')
                ->input('text', [
                    'placeHolder' => '0.00']) ?>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="col-md-7">
        <?= $form->field($service, 'services_description')->textarea(['rows' => 5])->label() ?>
    </div>
    
    <div class="col-md-7 text-right">
        <?= Html::button('Удалить', [
            'class' => 'btn btn-danger delete_service__bnt',
            'data-target' => '#delete_service',
            'data-toggle' => 'modal',
            'data-service' => $service->id,
            'data-service-name' => $service->services_name]) ?>
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end() ?>
</div>
<?= ModalWindowsManager::widget(['modal_view' => 'delete_service']) ?>