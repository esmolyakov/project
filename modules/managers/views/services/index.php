<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\modules\managers\widgets\ModalWindowsManager;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Услуги, главная
 */

$this->title = 'Услуги';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::a('Услуга (+)', ['services/create'], ['class' => 'btn btn-success btn-sm']) ?>
    
    <hr />
    <?php
        $form = ActiveForm::begin([
            'id' => 'search-form',
        ]);
    ?>
    
    <?= $form->field($search_model, '_input')
            ->input('text', [
                'placeHolder' => 'Поиск...',
                'id' => '_search-service'])
            ->label() ?>
    
    <?php ActiveForm::end() ?>
    
    <?= $this->render('data/grid_services', ['services' => $services]) ?>
</div>
<?= ModalWindowsManager::widget(['modal_view' => 'delete_service']) ?>