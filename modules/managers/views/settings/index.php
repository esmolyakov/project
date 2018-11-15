<?php

    use app\modules\managers\widgets\AlertsShow;

/* 
 * Тарифы, главная
 */

$this->title = 'Настройки';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    <?= AlertsShow::widget() ?>
    
</div>
