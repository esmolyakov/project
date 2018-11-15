<?php
    
    use yii\widgets\ActiveForm;
    use yii\widgets\Pjax;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;


/* 
 * Комментарии к заявке
 */

?>

<?php Pjax::begin(['id' => 'comments']) ?>
<div class="comments">
    <?php if (isset($comments_find)) : ?>
        <?php foreach ($comments_find as $comment) : ?>
    
            <span class="label label-default">
                <?= FormatHelpers::formatDateWithMonth($comment['date'])['date'] ?>
            </span>
            <br />

            <div class="col-8 col-sm-12">
                <strong> #USER ID <?= $comment['user'] ?></strong>
                <?= FormatHelpers::formatDateWithMonth($comment['date'])['time'] ?>
            </div>
            <div class="col-8 col-sm-2">
                <?php if ($comment['photo']) : ?>
                    <?= Html::img($comment['photo'], ['style' => 'width: 50px;']) ?>
                <?php else: ?>
                    <?= Html::img('@web/images/no-avatar.jpg', ['style' => 'width: 50px;']) ?>                            
                <?php endif; ?>
            </div>

            <div class="col-8 col-sm-10" style="background: #337ab7; padding: 5px; color: #fff; border-radius: 5px; position: relative; top: 5px;">
                <?= $comment['text'] ?>
            </div>
            <div class="clearfix"></div>
            <hr />
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php Pjax::end() ?>


<?php Pjax::begin(['id' => 'new_comment']) ?>
    <?php
        $form = ActiveForm::begin([
            'id' => 'add-comment',
            'options' => [
                'data-pjax' => true,
            ],
        ]);
    ?>
                    
    <?= $form->field($model, 'comments_text')
            ->textarea([
                'id' => 'comments-text',
                'placeHolder' => $model->getAttributeLabel('comments_text'), 
                'rows' => 6])
            ->label(false) ?>
                
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success btn__add_comment']) ?>

    <?php ActiveForm::end() ?>
<?php Pjax::end(); ?>

<?php
    $this->registerJs('
        $("document").ready(function(){
            $("#new_comment").on("pjax:end", function() {
                $.pjax.reload({container:"#comments"});
            });
        });
    ');
?>