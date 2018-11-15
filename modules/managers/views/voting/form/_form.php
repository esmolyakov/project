<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use kartik\datetime\DateTimePicker;
    use vova07\imperavi\Widget;
    use app\models\Questions;
    use app\models\Voting;

/*
 * Форма голосования
 */
?>


<div class="product-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-voting',
        'enableClientValidation' => false,
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <?= $model->errorSummary($form); ?>

    <fieldset>
        <legend>Голосование</legend>
        <div class="col-md-6">
            <?= $form->field($model->voting, 'voting_type')->radioList($type_voting)->label(false) ?>
            <?= $form->field($model->voting, 'voting_title')->textInput() ?>
            <?= $form->field($model->voting, 'voting_text')->widget(Widget::className(), [
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200,
                    'plugins' => [
                        'fullscreen',
                        'fontcolor',
                        'table',
                        'fontsize',
                    ],
                ],
            ]) ?>


            <?= $form->field($model->voting, 'voting_date_start')
                        ->widget(DateTimePicker::className(), [
                            'id' => 'date_voting_start',
                            'language' => 'ru',
                            'options' => [
                                'placeholder' => $model->voting->getAttributeLabel('voting_date_start'),
                            ],
                            'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoClose' => true,
                                'format' => 'yyyy-mm-dd hh:ii',
                            ]
                        ]) ?>

                <?= $form->field($model->voting, 'voting_date_end')
                        ->widget(DateTimePicker::className(), [
                            'id' => 'date_voting_end',
                            'language' => 'ru',
                            'options' => [
                                'placeholder' => $model->voting->getAttributeLabel('voting_date_start'),
                            ],
                            'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoClose' => true,
                                'format' => 'yyyy-mm-dd hh:ii',
                            ]
                        ]) ?>   
        </div>
        
        <div class="col-md-6">
            <div class="text-center">
                <?= Html::img($model->voting->image, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => $model->voting->voting_title, 'width' => 150]) ?>
            </div>
            <br />
                <?= $form->field($model, 'imageFile')->input('file', ['id' => 'btnLoad'])->label(false) ?>
        </div>
        
    </fieldset>

    <fieldset>
        <legend>Вопросы
            <?php if ($model->voting->status !== 1) : ?>
                <?= Html::a('Добавить вопрос', 'javascript:void(0);', [
                        'id' => 'voting-new-question-button', 
                        'class' => 'pull-right btn btn-default btn-xs'
                    ])
                ?>
            <?php endif; ?>
        </legend>
        <?php $question = new Questions(); ?>
        <table id="voting-questions" class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th><?= $question->getAttributeLabel('questions_text') ?></th>
                    <td>&nbsp;</td>
                </tr>
            </thead>
            <tbody>
                <?php // Формируем поле для ввода вопроса для текущего голосования ?>
                <?php foreach ($model->questions as $key => $_question) : ?>
                    <tr>
                    <?= $this->render('new_question', [
                            'key' => $_question->isNewRecord ? (strpos($key, 'new') !== false ? $key : 'new' . $key) : $_question->questions_id,
                            'form' => $form,
                            'question' => $_question,
                            'status' => $model->voting->status,
                        ]) 
                    ?>
                    </tr>
                <?php endforeach; ?>
                <?php // Поля для нового вопроса ?>
                <tr id="voting-new-question-block" style="display: none;">
                    <?= $this->render('new_question', [
                            'key' => '__id__',
                            'form' => $form,
                            'question' => $question,
                            'status' => $model->voting->status,
                        ])
                    ?>
                </tr>
            </tbody>
        </table>
        

        <?php ob_start(); // включаем буферизацию для js ?>

        <script>
            
            // Добавление кнопки нового вопроса
            var question_k = <?php echo isset($key) ? str_replace('new', '', $key) : 0; ?>;
            $('#voting-new-question-button').on('click', function () {
                question_k += 1;
                $('#voting-questions').find('tbody')
                  .append('<tr>' + $('#voting-new-question-block').html().replace(/__id__/g, 'new' + question_k) + '</tr>');
            });
            
            /*
             * Запрос на удаление вопроса
             */
            var elemQiestion;
            $(document).on('click', '.voting-remove-question-button', function () {
                elemQiestion = $(this).closest('tbody tr');
            });
            $('.delete_question').on('click', function(){
                elemQiestion.remove();
            });


            <?php
            if (!Yii::$app->request->isPost && $model->voting->isNewRecord) 
              echo "$('#voting-new-question-button').click();";
            ?>
        </script>
        <?php $this->registerJs(str_replace(['<script>', '</script>'], '', ob_get_clean())); ?>

    </fieldset>

    <?= Html::submitButton($model->voting->isNewRecord ? 'Опубликовать' : 'Сохранить', ['class' => 'btn btn-primary']); ?>
    <?php ActiveForm::end(); ?>

</div>