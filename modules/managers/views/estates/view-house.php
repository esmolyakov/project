<?php

    use app\modules\managers\widgets\AlertsShow;

/* 
 * Просмотр и редактирование записи
 * Жилой комплекс + Дом
 */
$this->title = 'Жилой объект (+)';
?>

<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('_form/create_house', [
            'model' => $model,
            'estates_list' => $estates_list,
            'upload_files' => $upload_files]) ?>
    
</div>