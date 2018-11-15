<?php


/* 
 * Собственники
 */

$this->title = 'Клиенты';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= $this->render('data/grid', ['client_list' => $client_list]) ?>
    
</div>