<?php

    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;

/* 
 * Вид отрисовки списка всех созданных голосований
 */
?>
<?php foreach ($view_all_voting as $voting) : ?>
    <div class="col-md-12">
        <div class="col-md-2">
            <?= Html::img('@web/' . $voting['voting_image'], [
                    'alt' => $voting['voting_title'], 
                    'class' => 'img-thumbnail', 
                    'width' => '150', 
                    'height' => '150']) ?>
            
            <?= Html::button('Удалить', [
                    'class' => 'btn btn-danger btn-sm',
                    'data-toggle' => 'modal',
                    'data-target' => '#delete_voting_manager',
                    'data-voting' => $voting['voting_id']]) ?>
        </div>
        <div class="col-md-10">
            <p>
                <?= Html::a($voting['voting_title'], ['voting/view', 'voting' => $voting['voting_id']]) ?>
                <span class="label label-primary">ID <?= $voting['voting_id'] ?></span>
            </p>
            <p>
                <span class="label label-success"><?= FormatHelpers::statusNameVoting($voting['status']) ?></span>
                <b>Дата начала голосования:</b> <?= FormatHelpers::formatDate($voting['voting_date_start'], true, 1) ?>
                <b>Дата окончания голосования:</b> <?= FormatHelpers::formatDate($voting['voting_date_end'], true, 1) ?>
                <b>Проголосовало:</b> #COUNT
            </p>
            <hr />
            <?= HtmlPurifier::process(strip_tags($voting['voting_text'])) ?>
        </div>
    </div>

    <div class="col-md-12 text-right" style="padding: 5px;">
        <p class="small">
            Автор: <?= FormatFullNameUser::nameEmployerByUserID($voting['voting_user_id']) ?>
            <b>Дата создания:</b> <?= FormatHelpers::formatDate($voting['created_at'], false) ?>
            <b>Последний раз редактировалось: </b> <?= FormatHelpers::formatDate($voting['updated_at'], false) ?>
            <?= Html::a('Ознакомиться', ['voting/view', 'voting' => $voting['voting_id']]) ?>
        </p>
    </div>
    <div class="clearfix"></div>
    <hr />
<?php endforeach; ?>

    
<?= LinkPager::widget([
        'pagination' => $pages
    ]);
?>