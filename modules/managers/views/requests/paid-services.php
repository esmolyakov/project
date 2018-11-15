<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Платные услуги, главная
 */
$this->title = 'Платные услуги';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::button('Заявка (+)', [
        'class' => 'btn btn-success btn-sm create-paid-request',
        'data-target' => '#create-new-paid-requests',
        'data-toggle' => 'modal']) ?>
    
    <hr />
    
    <?= $this->render('data/grid_paid_requests', [
        'paid_requests' => $paid_requests
    ]) ?>
    
</div>

<?= $this->render('form/create_paid_request', [
        'model' => $model,
        'servise_category' => $servise_category,
        'servise_name' => $servise_name,
        'flat' => $flat,]) ?></div>