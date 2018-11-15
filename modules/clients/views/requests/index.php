<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\bootstrap4\Modal;
    use yii\helpers\Url;
    use app\modules\clients\widgets\StatusRequest;
    use app\modules\clients\widgets\AlertsShow;
    
/* 
 * Заявки (Общая страница)
 */
$this->title = 'Мои заявки';
?>

<div class="table-container">
    <div class="account-info-table-container req-table-container">
        
        <?= $this->render('data/grid', ['all_requests' => $all_requests]); ?>
        
        <div class="fixed-bottom req-fixed-bottom-btn-group mx-auto ">
            <?= Html::button('', ['class' => 'add-req-fixed-btn btn-link', 'data-toggle' => 'modal', 'data-target' => '#add-request-modal']) ?>
        </div>
    </div>
</div>

<?= $this->render('form/add-request', ['model' => $model, 'type_requests' => $type_requests]) ?>

<?php
/* Фильтр заявок пользователя по 
 * ID лицевого счета, типу и статусу заявки
 */
$this->registerJs("    
    $('#account_number, .current__account_list').on('change', function(e) {
        
        e.preventDefault();
        
        var type_id = $('#account_number').val();
        var account_id = $('.current__account_list').val();
        var status = $('.list-group-item.active').data('status');

        $.ajax({
            url: 'filter-by-type-request?type_id=' + type_id + '&account_id=' + account_id + '&status=' + status,
            method: 'POST',
            data: {
                type_id: type_id,
                account_id: account_id,
                status: status,
            },
            success: function(data){
                if (data.status === false) {
                    console.log('Ошибка при передаче данных');
                } else {
                    $('.grid-view').html(data);
                }
            }
        });
    });
");
?>