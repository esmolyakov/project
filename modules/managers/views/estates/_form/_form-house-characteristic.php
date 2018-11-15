<?php
    use app\models\ChangePasswordForm;
    use yii\helpers\Html;
?>
<td>
    <?= $form->field($characteristic, 'characteristics_name')->textInput([
        'id' => "CharacteristicsHouse_{$key}_characteristics_name",
        'name' => "CharacteristicsHouse[$key][characteristics_name]",
    ])->label(false) ?>
</td>
<td>
    <?= $form->field($characteristic, 'characteristics_value')->textInput([
        'id' => "CharacteristicsHouse_{$key}_characteristics_value",
        'name' => "CharacteristicsHouse[$key][characteristics_value]",
    ])->label(false) ?>
</td>
<td>
    <?= Html::a('Удалить', 'javascript:void(0);', [
      'class' => 'house-remove-characteristic-button btn btn-default btn-xs',
    ]) ?>
</td>