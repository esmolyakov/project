<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Новая услуга
 */
$this->title = 'Услуга (+)';
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
            <?= Html::img($model->service_image, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => $model->service_name, 'width' => 150]) ?>
        </div>
        <br />
        <?= $form->field($model, 'service_image')
                ->input('file', ['id' => 'btnLoad'])
                ->label(false) ?>
    </div>
    
    <div class="col-md-5">
        <?= $form->field($model, 'service_type')
                ->radioList($service_types)->label(false) ?>
        
        <?= $form->field($model, 'service_name')
                ->input('text', [
                    'placeHolder' => $model->getAttributeLabel('service_name'),]) ?>
        
        <?= $form->field($model, 'service_category')
                ->dropDownList($service_categories, [
                    'prompt' => 'Выберите услугу из списка...',]) ?>
        
        <?= $form->field($model, 'service_unit')
                ->dropDownList($units, [
                    'prompt' => 'Выбрать из списка...']) ?>
        
        <?= $form->field($model, 'service_cost')
                ->input('text', [
                    'placeHolder' => '0.00']) ?>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="col-md-7">
        <?= $form->field($model, 'service_description')->textarea(['rows' => 5])->label() ?>
    </div>
    
    <div class="col-md-7 text-right">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end() ?>
</div>