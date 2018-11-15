<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Завяки, главная
 */
$this->title = 'Заявки';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::button('Заявка (+)', [
        'class' => 'btn btn-success btn-sm create-request',
        'data-target' => '#create-new-requests',
        'data-toggle' => 'modal']) ?>
    
    <hr />
    
    <?= $this->render('data/grid_requests', [
        'requests' => $requests
    ]) ?>
    
</div>

<?= $this->render('form/create_request', [
        'model' => $model,
        'type_request' => $type_request,
        'flat' => $flat,]) ?>