<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Houses */

$this->title = 'Update Houses: ' . $model->houses_id;
$this->params['breadcrumbs'][] = ['label' => 'Houses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->houses_id, 'url' => ['view', 'id' => $model->houses_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="houses-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
