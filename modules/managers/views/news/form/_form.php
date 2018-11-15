<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use vova07\imperavi\Widget;

/* 
 * Создание новой новости
 */
?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'news-form',
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]);
    ?>
    
    <div class="col-md-6">
        
        <?= $form->field($model, 'status')
                ->radioList($status_publish, [
                    'id' => 'for_whom_news'])
                ->label(false) ?>
    </div>

    <div class="col-md-6" style="background: #ffcce6; padding: 10px;">

        <?= $form->field($model, 'isAdvert')
                ->checkbox([
                    'id' => 'check_advet'])
                ->label(false) ?>
        
        <?= $form->field($model, 'partner')
                ->dropDownList($parnters, [
                    'id' => 'parnters_list',
                    'prompt' => 'Выбрать из списка...',
                    'disabled' => true,])
                ->label() ?>
        
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'house')->dropDownList($houses, [
            'prompt' => 'Выбрать из списка...',
            'id' => 'adress_list',
        ]) ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'rubric')->dropDownList($rubrics, [
            'prompt' => 'Выбрать из списка',]) ?>
    </div>
    
    <div class="col-md-2">
        <div class="text-center">
            <?= Html::img($model->preview, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => $model->title, 'width' => 150]) ?>
        </div>
        <br />
        <?= $form->field($model, 'preview')
                ->input('file', ['id' => 'btnLoad'])
                ->label(false) ?>
    </div>    
    
    <div class="col-md-10">
        <?= $form->field($model, 'title')
                ->input('text', [
                    'placeHolder' => $model->getAttributeLabel('title'),])
                ->label() ?>
        
        <?= $form->field($model, 'text')->widget(Widget::className(), [
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200,
                    'imageUpload' => Url::to(['/managers/news/image-upload']),
                    'imageDelete' => Url::to(['/managers/news/file-delete']),
                    'plugins' => [
                        'fullscreen',
                        'imagemanager',
                        'fontcolor',
                        'table',
                        'fontsize',
                    ],
                ],
            ]) ?>
        
    </div>
    
    <div class="col-md-12">
        <br />
        <?= $form->field($model, 'files[]')->input('file', ['multiple' => true])->label() ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'isPrivateOffice')
                ->radioList($notice, [
                    'id' => 'type_notice'])
                ->label(false) ?>

    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'isNotice')->checkboxList($type_notice, [
            'item' => function ($index, $label, $name, $checked, $value) {
                return Html::checkbox($name, $checked, [
                    'value' => $value,
                    'id' => 'is_notice_' . $index,
                    'disabled' => 'disabled'
                ]) . $label;
            }
        ])->label(false) ?>

    </div>
    
    <div class="clearfix"></div>
    
    <div class="col-md-12 text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
     
    
    <?php ActiveForm::end(); ?>
    