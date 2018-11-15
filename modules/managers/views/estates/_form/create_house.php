<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\models\CharacteristicsHouse;
    use app\helpers\FormatHelpers;

/* 
 * ФОрма создание нового дома
 */
?>
    <?php 
        $form = ActiveForm::begin([
            'id' => 'create-house',
            'enableClientValidation' => false, 
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]); 
    ?>

    <?= $model->errorSummary($form); ?>

<div class="col-md-6">
    <fieldset>
        <legend>Дом</legend>
        
        <?= $form->field($model->houses, 'houses_estate_name_id')->dropDownList($estates_list, ['prompt' => 'Выбрать из списка...']) ?>
        
        <?= $form->field($model->houses, 'houses_street')->input('text')->label() ?>
        <?= $form->field($model->houses, 'houses_number_house')->input('text')->label() ?>
        <?= $form->field($model->houses, 'houses_description')->textarea()->label() ?>
        
    </fieldset>
</div>
    
<div class="col-md-6">
    <fieldset>
        <legend>Характеристики
            <?= Html::a('Добавить характерситику', 'javascript:void(0);', [
                    'id' => 'house-new-characteristic-button', 
                    'class' => 'pull-right btn btn-default btn-xs'
                ])
            ?>
        </legend>
        <?php
            $сharacteristic = new CharacteristicsHouse();
            $сharacteristic->loadDefaultValues();
        ?>
        
        <table id="house-characteristic" class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th><?= $сharacteristic->getAttributeLabel('characteristics_name') ?></th>
                    <th><?= $сharacteristic->getAttributeLabel('characteristics_value') ?></th>
                    <td>&nbsp;</td>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($model->characteristicsHouse as $key => $_characteristic) : ?>
                <tr>
                <?= $this->render('_form-house-characteristic', [
                        'key' => $_characteristic->isNewRecord ? (strpos($key, 'new') !== false ? $key : 'new' . $key) : $_characteristic->characteristics_id,
                        'form' => $form,
                        'characteristic' => $_characteristic,
                    ]);
                ?>
                </tr>
                <?php endforeach; ?>

                <tr id="house-new-characteristic-block" style="display: none;">
                <?= $this->render('_form-house-characteristic', [
                        'key' => '__id__',
                        'form' => $form,
                        'characteristic' => $сharacteristic,
                    ]);
                ?>
                </tr>
            </tbody>
        </table>
        
        <?php ob_start(); ?>
        <script>
            var charact_k = <?php echo isset($key) ? str_replace('new', '', $key) : 0; ?>;
            $('#house-new-characteristic-button').on('click', function () {
                charact_k += 1;
                $('#house-characteristic').find('tbody')
                  .append('<tr>' + $('#house-new-characteristic-block').html().replace(/__id__/g, 'new' + charact_k) + '</tr>');
            });
            
            $(document).on('click', '.house-remove-characteristic-button', function () {
                $(this).closest('tbody tr').remove();
            });
            
            <?php 
                if (!Yii::$app->request->isPost && $model->houses->isNewRecord)
                    echo "$('#house-new-characteristic-button').click();";
            ?>
            
        </script>
        <?php $this->registerJs(str_replace(['<script>', '</script>'], '', ob_get_clean())); ?>

    </fieldset>
    
   <fieldset>
        <legend>
            Вложения 
            <span class="small">
            #TODO Доделать удаление файлов, изпользуя метод с главной страниц Жилого массива
            #TODO Или прикрутить виджет
            </span>
            <a href="#upload-files" class="btn btn-primary btn-sm pull-right" data-toggle="collapse">
                <span class="glyphicon glyphicon-menu-down"></span>
            </a> 
        </legend>
        <div id="upload-files" class="collapse">
            <?= $form->field($model->houses, 'upload_files[]')->input('file', ['multiple' => true])->label() ?>
            <br />
            <?php if (isset($upload_files) && $upload_files) : ?>
                <?php foreach ($upload_files as $file) : ?>
                    <?= FormatHelpers::formatUrlByDoc($file['name'], $file['filePath']) ?>
                    <?= Html::button('Удалить', [
                            'class' => 'btn btn-link btn-sm',
                            'id' => 'delete_file__house',
                            'data-files' => $file['id']]) ?>
                <?php endforeach; ?>               
            <?php endif; ?>
        </div>
   </fieldset>   
    
    
</div>
<div class="col-md-12 text-right">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
</div>

<?php ActiveForm::end(); ?>

