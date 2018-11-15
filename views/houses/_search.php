<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HousesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="houses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'houses_id') ?>

    <?php // = $form->field($model, 'houses_name') ?>

    <?php // = $form->field($model, 'houses_town') ?>

    <?php // = $form->field($model, 'houses_street') ?>

    <?php // = $form->field($model, 'houses_number_house') ?>

    <?php // echo $form->field($model, 'houses_porch') ?>

    <?php // echo $form->field($model, 'houses_floor') ?>

    <?php // echo $form->field($model, 'houses_flat') ?>

    <?php // echo $form->field($model, 'houses_rooms') ?>

    <?php // echo $form->field($model, 'houses_square') ?>

    <?php // echo $form->field($model, 'houses_account_id') ?>

    <?php // echo $form->field($model, 'houses_client_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
