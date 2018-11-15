<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\ModalWindowsManager;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Голосование, главная
 */

$this->title = 'Голосование';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::a('Голосование (+)', ['voting/create'], ['class' => 'btn btn-success btn-sm']) ?>
    
    <hr />
    
    <?= $this->render('data/view_all_voting', ['view_all_voting' => $view_all_voting, 'pages' => $pages]) ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_voting']) ?>