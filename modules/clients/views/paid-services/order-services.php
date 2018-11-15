<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    
/* 
 * Заявки (Обзая страница)
 */
$this->title = 'История услуг';
?>


<div class=table-applications>
    
    <?= $this->render('data/grid', ['all_orders' => $all_orders]) ?>
    
</div>



<?php /*
<div class="paid-services-default-index">
    <h1><?= $this->title ?></h1>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'search-form',
            'options' => [
                'class' => 'form-horizontal',
            ]
        ]);
    ?>
    
    <div class="form-group">
        <label class="control-label col-sm-9" for="email">Поиск по исполнителю</label>
        <div class="col-sm-3 text-right">
            <?= $form->field($_search, '_input')
                    ->input('text', [
                        'placeHolder' => $_search->getAttributeLabel('_input'),
                        'id' => '_search-input'])
                    ->label(false) ?>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>
    
    <div class="grid-view">
        <?= $this->render('data/grid', ['all_orders' => $all_orders]) ?>
    </div>
    
</div>
 */ ?>

<?php
/* Фильтр заявок пользователя по 
 * ID лицевого счета, типу и статусу заявки
 */
$this->registerJs("    
    $('.current__account_list').on('change', function(e) {
        e.preventDefault();
        var account_id = $('.current__account_list').val();
        
        $.ajax({
            url: 'filter-by-account?account_id=' + account_id,
            method: 'POST',
            data: {
                account_id: account_id,
            },
            success: function(response) {
                if (response.status === false) {
                    $('.grid-view').html('Возникла ошибка при передаче данных. Обновите страницу, нажав на клавиатуре клавишу F5');
                } else {
                    $('.grid-view').html(response.data);
                }
            }
        });
    });
");
?>
