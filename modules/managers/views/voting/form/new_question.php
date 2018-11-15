<?php

    use app\models\Questions;
    use yii\helpers\Html;
    use app\modules\managers\widgets\Vote;
    
?>
<td>
    <?= $form->field($question, 'questions_text')->textInput([
        'id' => "Questions_{$key}_questions_text",
        'name' => "Questions[$key][questions_text]",
    ])->label(false) ?>
    
    <?= \app\modules\managers\widgets\Vote::widget(['question_id' => $key]) ?>
</td>
<td>
    <?php if ($status !== 1) : ?>
        <?= Html::button('Удалить вопрос', [
                'class' => 'voting-remove-question-button btn btn-default btn-xs',
                'data-toggle' => 'modal',
                'data-target' => '#delete_question_vote_message',
        ]) ?>
    <?php endif; ?>
</td>