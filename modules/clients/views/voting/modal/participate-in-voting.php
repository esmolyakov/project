<?php

    use yii\bootstrap4\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    

/* 
 * Модальное окно, подтверждения СМС кода на участие в регистрации
 */
?>


<?php 
Modal::begin([
    'id' => 'participate-in-voting-' . $voting_id,
    'title' => '',
    'closeButton' => [
        'class' => 'close add-acc-modal-close-btn req rent-info__btn_close',
    ],
]);
?>

<h4 class="modal-title modal-acc-h mx-auto continuation">
    Для участия в голосовании введите СМС код, <br />
    который был выслан на ваш номер мобильного телефона
</h4>


<div class="sms-cod">
    <?php 
        $form = ActiveForm::begin([
            'id' => 'fill_sms_to_participate',
            'validateOnSubmit' => false,
            'validateOnChange' => false,
            'validateOnBlur' => false,            
            'options' => [
                'class' => 'form-inline',
            ]
        ]); 
    ?>
                
    <?= $form->field($model, 'number1')->input('text', ['class' => 'number-sms', 'maxlength' => 1, 'size' => 1])->label(false) ?>
    <?= $form->field($model, 'number2')->input('text', ['class' => 'number-sms', 'maxlength' => 1, 'size' => 1])->label(false) ?>
    <?= $form->field($model, 'number3')->input('text', ['class' => 'number-sms', 'maxlength' => 1, 'size' => 1])->label(false) ?>
    <?= $form->field($model, 'number4')->input('text', ['class' => 'number-sms', 'maxlength' => 1, 'size' => 1])->label(false) ?>
    <?= $form->field($model, 'number5')->input('text', ['class' => 'number-sms', 'maxlength' => 1, 'size' => 1])->label(false) ?>
                
</div>
            
<div class="to-send">
    <?= Html::button('Отправить код еще раз', [
            'class' => 'again-bt',
            'id' => 'repeat_sms_code',
            'data-voting' => $voting_id,
        ]) ?>
</div>
<div class="to-send-message">
    <span class="repeat_sms_code-message"></span>
</div>
                
<div class="modal-footer no-border bt-sms">
    <?= Html::submitButton('Продолжить', ['class' => 'btn blue-outline-btn white-btn mx-auto bt-bottom']) ?>
    
    <?= Html::button('Отмена', [
            'data-dismiss' => 'modal', 
            'class' => 'btn red-outline-btn bt-bottom2',
            'id' => 'renouncement_participate',
            'data-voting' => $voting_id,
        ]) ?>
                
</div>
            
<?php ActiveForm::end(); ?>

<?php
Modal::end();
?>

<?php /*
<div class="modal fade enter-sms" id="participate-in-voting-<?= $voting_id ?>" style="padding-right: 17px; display: block;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-sms">
            <div class="modal-header no-border ">
                <h4 class="modal-title modal-acc-h mx-auto continuation">
                    Для участия в голосовании введите СМС код, <br />
                    который был выслан на ваш номер мобильного телефона
                </h4>
                <button type="button" id="renouncement_participate_close" class="close add-acc-modal-close-btn m-0" data-dismiss="modal" data-voting=<?= $voting_id ?>>
                    &times;
                </button>
            </div>
            <div class="sms-cod">
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'fill_sms_to_participate',
                        'validateOnSubmit' => false,
                        'validateOnChange' => false,
                        'validateOnBlur' => false,            
                        'options' => [
                            'class' => 'form-inline',
                        ]
                    ]); 
                ?>
                
                <?= $form->field($model, 'number1')->input('text', ['class' => 'number-sms', 'maxlength' => 1, 'size' => 1])->label(false) ?>
                <?= $form->field($model, 'number2')->input('text', ['class' => 'number-sms', 'maxlength' => 1, 'size' => 1])->label(false) ?>
                <?= $form->field($model, 'number3')->input('text', ['class' => 'number-sms', 'maxlength' => 1, 'size' => 1])->label(false) ?>
                <?= $form->field($model, 'number4')->input('text', ['class' => 'number-sms', 'maxlength' => 1, 'size' => 1])->label(false) ?>
                <?= $form->field($model, 'number5')->input('text', ['class' => 'number-sms', 'maxlength' => 1, 'size' => 1])->label(false) ?>
                
            </div>
            
            <div class="to-send">
                <?= Html::button('Отправить код еще раз', [
                        'class' => 'again-bt',
                        'id' => 'repeat_sms_code',
                        'data-voting' => $voting_id,
                    ]) ?>
            </div>
            <div class="to-send-message">
                <span class="repeat_sms_code-message"></span>
            </div>
                
            <div class="modal-footer no-border bt-sms">
                
                <?= Html::submitButton('Продолжить', ['class' => 'btn blue-outline-btn white-btn mx-auto bt-bottom']) ?>
    
                <?= Html::button('Отмена', [
                        'data-dismiss' => 'modal', 
                        'class' => 'btn red-outline-btn bt-bottom2',
                        'id' => 'renouncement_participate',
                        'data-voting' => $voting_id,
                    ]) ?>
                
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
 * 
 * 
 */ ?>