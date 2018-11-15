<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Houses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="houses-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'houses_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'houses_town')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'houses_street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'houses_number_house')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'houses_porch')->textInput() ?>

    <?= $form->field($model, 'houses_floor')->textInput() ?>

    <?= $form->field($model, 'houses_flat')->textInput() ?>

    <?= $form->field($model, 'houses_rooms')->textInput() ?>

    <?= $form->field($model, 'houses_square')->textInput() ?>

    <?= $form->field($model, 'houses_account_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
