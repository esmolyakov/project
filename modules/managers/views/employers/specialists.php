<?php

    use app\modules\managers\widgets\AlertsShow;
    use yii\helpers\Html;
    use app\modules\managers\widgets\ModalWindowsManager;
    use yii\widgets\ActiveForm;

/* 
 * Специалисты
 */

$this->title = 'Специалисты';
?>
<div class="dispatchers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::a('Специалисты (+)', ['employers/add-specialist'], ['class' => 'btn btn-success btn-sm']) ?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'search-form',
        ]);
    ?>
        
        <?= $form->field($search_model, '_input')
                ->input('text', [
                    'placeHolder' => 'Поиск...',
                    'id' => '_search-specialist',])
                ->label() ?>
    
    <?php ActiveForm::end(); ?>
    
    <hr />
    <?= $this->render('data/grid_specialists', ['specialists' => $specialists]) ?>
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_specialist']) ?>