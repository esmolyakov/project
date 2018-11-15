<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use vova07\imperavi\Widget;
    use app\helpers\FormatHelpers;
    use app\modules\managers\widgets\ModalWindowsManager;    

// Тип публикации, для блокировки чекбоксов смс, емайл, пуш уведомления    
$status_checkbox = $model->isPrivateOffice ? false : true;
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
        <?= $form->field($model, 'news_status')
                ->radioList($status_publish, [
                    'id' => 'for_whom_news'])
                ->label(false) ?>
        
    </div>

    <div class="col-md-6" style="background: #ffcce6; padding: 10px;">

        <?= $form->field($model, 'isAdvert')
                ->checkbox([
                    'id' => 'check_advet'])
                ->label(false) ?>
        
        <?= $form->field($model, 'news_partner_id')
                ->dropDownList($parnters, [
                    'id' => 'parnters_list',
                    'prompt' => 'Выбрать из списка...',
                    'disabled' => $model->isAdvert ? false : true,])
                ->label() ?>
        
    </div>
    
    <div class="col-md-6">
        
        <?= $form->field($model, 'news_house_id')->dropDownList($houses, [
            'id' => 'adress_list',
        ]) ?>
        
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'news_type_rubric_id')->dropDownList($rubrics) ?>
    </div>
    
    <div class="col-md-2">
        <div class="text-center">
            <?= Html::img($model->preview, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => $model->news_title, 'width' => 150]) ?>
        </div>
        <br />
        <?= $form->field($model, 'news_preview')
                ->input('file', ['id' => 'btnLoad'])
                ->label(false) ?>
    </div>    
    
    <div class="col-md-10">
        <?= $form->field($model, 'news_title')
                ->input('text', [
                    'placeHolder' => $model->getAttributeLabel('news_title'),])
                ->label() ?>
        
        <?= $form->field($model, 'news_text')->widget(Widget::className(), [
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
        <?= $form->field($model, 'files[]')->input('file', ['multiple' => true])->label() ?>
        <hr />
        <?php if (isset($docs) && count($docs) > 0) : ?>
            <?php foreach ($docs as $doc) : ?>
                <?= FormatHelpers::formatUrlByDoc($doc['name'], $doc['filePath']) ?>
                <?= Html::button('Удалить', [
                        'class' => 'btn btn-link btn-sm delete_file',
                        'data-files' => $doc['id'],]) ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <hr />
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'isPrivateOffice')
                ->radioList($notice, [
                    'id' => 'type_notice'])
                ->label(false) ?>
    </div>
    
    <div class="col-md-6">
        
        <?= $form->field($model, 'isSMS')
                ->checkbox([
                    'id' => 'is_notice',
                    'disabled' => $status_checkbox])
                ->label(false) ?>
        
        <?= $form->field($model, 'isEmail')
                ->checkbox([
                    'id' => 'is_notice',
                    'disabled' => $status_checkbox])
                ->label(false) ?>
        
        <?= $form->field($model, 'isPush')
                ->checkbox([
                    'id' => 'is_notice',
                    'disabled' => $status_checkbox])
                ->label(false) ?>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="col-md-12 text-right">
        
        <?= Html::button('Удалить', [
            'class' => 'btn btn-danger',
            'data-target' => '#delete_news_manager',
            'data-toggle' => 'modal',
            'data-news' => $model->news_id,
            'data-is-advert' => $model->isAdvert]) ?>
        
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        
    </div>
     
    
    <?php ActiveForm::end(); ?>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_news']) ?>    