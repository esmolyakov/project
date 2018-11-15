<?php

    use app\modules\managers\widgets\AlertsShow;

/* 
 * Создание новой новости
 */
$this->title = 'Публикация (+)';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('form/_form', [
        'model' => $model,
        'status_publish' => $status_publish,
        'notice' => $notice,
        'type_notice' => $type_notice,
        'rubrics' => $rubrics,
        'houses' => $houses,
        'parnters' => $parnters]) ?>
    
</div>