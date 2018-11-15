<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Новости, нлавная страница
 */
$this->title = 'Реклама';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    <?= AlertsShow::widget() ?>
    <?= Html::a('Реклама (+)', ['news/create'], [
        'class' => 'btn btn-sm btn-success',])
    ?>
    
    <?= $this->render('data/grid_adverts', [
            'all_adverts' => $all_adverts,
        ]) 
    ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_news']) ?>