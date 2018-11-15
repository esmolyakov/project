<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
    use app\helpers\FormatHelpers;
/* 
 * Форма добавления комментариев
 */
?>

<?php Pjax::begin(['id' => 'comments']) ?>
<div class="comments">
    <?php if (isset($comments_find)) : ?>
        <?php foreach ($comments_find as $key => $comment) : ?>
            <div class="">
                <span class="badge badge-darkblue request-chat-badge-date">
                    <?php // = FormatHelpers::formatDate($comment->created_at, false, 1, true) ?>
                </span>
            </div>
            <div class="row">
                <?= Html::img($comment['user']->photo, ['class' => 'rounded-circle request-chat-icon']) ?>
                <div class="chat-txt-block">
                    <p class="chat-name">
                        <?= $comment['user']['client']->clients_name ? $comment['user']['client']->clients_name : $comment['user']['employer']->employers_name ?>
                        <?= $current_date ?>
                    </p>
                    <?= $comment->comments_text ?>
                </div>
                <span class="chat-time my-auto">
                    <?= FormatHelpers::formatDate($comment->created_at, true, 0, true) ?>
                </span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
</div>
<?php Pjax::end() ?>  

<?php Pjax::begin(['id' => 'new_note']) ?>
<div class="chat-msg">
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'add-comment',
            'options' => [
                'data-pjax' => true,
            ],
        ]);
    ?>
    
    <div class="alert alert-danger message_error" style="display: none"></div>
                    
    <?= $form->field($model, 'comments_text')
            ->textarea([
                'placeHolder' => $model->getAttributeLabel('comments_text'), 
                'rows' => 7])
            ->label(false) ?>    
    
    <?= Html::submitButton('Отправить', ['class' => 'd-block text-right chat-btn ml-auto']) ?>
    
    <?php ActiveForm::end(); ?>
    
</div>
<?php Pjax::end() ?>  

<?php
    $this->registerJs('
        $("document").ready(function(){
            $("#new_note").on("pjax:end", function() {
                $.pjax.reload({container:"#comments"});
            });
        });
    ');
?>