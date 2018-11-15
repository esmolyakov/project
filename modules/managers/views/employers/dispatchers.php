<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Диспетчеры
 */

$this->title = 'Диспетчеры';
?>
<div class="dispatchers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::a('Диспетчер (+)', ['employers/add-dispatcher'], ['class' => 'btn btn-success btn-sm']) ?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'search-form',
        ]);
    ?>
        
        <?= $form->field($search_model, '_input')
                ->input('text', [
                    'placeHolder' => 'Поиск...',
                    'id' => '_search-dispatcher',])
                ->label() ?>
    
    <?php ActiveForm::end(); ?>
    
    <hr />
    <?= $this->render('data/grid_dispatchers', ['dispatchers' => $dispatchers]) ?>
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_dispatcher']) ?>