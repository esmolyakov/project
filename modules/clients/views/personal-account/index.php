<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
/* 
 * Лицевой счет / Общая информация
 */
$this->title = 'Общая информация';
?>

<div class="account-info-table-container">
    <?= $this->render('data/list', ['account_info' => $account_info]); ?>
    <?php if (Yii::$app->user->can('clients')) : ?>
        <div>
            <?= Html::button('Добавить лицевой счет', [
                    'class' => 'blue-btn mx-auto text-center add-acc-btn',
                    'data-toggle' => 'modal',
                    'data-target' => '#create-account-modal']) ?>
        </div>
        <?= $this->render('form/create_account', ['model' => $model]) ?>
    <?php endif; ?>
</div>

<?php /*
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>

    <div class="col-md-6">
        <?= Html::dropDownList('_list-account-all', $this->context->_choosing, $account_all, [
            'class' => 'form-control _list-account-all',
            'data-client' => $account_info['clients_id']]) ?>
        <br />
    </div>
    
    <div class="col-md-6 text-right">
        <?php if (Yii::$app->user->can('clients')) : ?>
            <?= Html::a('Добавить лицевой счет', Url::to(['personal-account/show-add-form']), ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
    </div>
    <div class="clearfix"></div>    
    
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Информация по лицевому счету</strong></div>
            <div class="panel-body">
                <?= $this->render('_data-filter/list', ['account_info' => $account_info]); ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Информаци об оплате</strong>                    
                </div>
            </div>            
            <div class="panel-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Китанция № 00/0000</td>
                            <td>Сумма: 0 000,00</td>
                            <td>
                                <?= Html::button('Оплатить', ['class' => 'btn btn-success']) ?>
                            </td>
                        </tr>          
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</div>
*/
?>


<?php
/*
 * Смена информации о лицевом счете,*
 * dropDownList _list-account-all
 */    
$this->registerJs("
    $('.current__account_list, ._list-account-all').on('change', function() {
        var accountId = $(this).val();
        var clientId = $('._list-account-all').data('client');
        
        $.ajax({
            url: 'list',
            method: 'POST',
            type: 'json',
            data: {
                accountId: accountId,
                clientId: clientId
            },
            success: function(response) {
                $('#account-info').html(response.data);
            },
            error: function() {
                console.log('Error #1000-07');
            },
        });
    });
")
?>