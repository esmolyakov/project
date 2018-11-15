<?php

    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Просмотр/редактирование публикации
 */
$this->title = $news->news_title;
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <p class="label label-warning">
        <?= FormatHelpers::formatDate($news->created_at) ?>
    </p>
    <br />
    Последний раз редактировалось: <p class="label label-success">
        <?= FormatHelpers::formatDate($news->updated_at, true) ?>
    </p>
    <br />
    Автор: <?= FormatFullNameUser::nameEmployerByUserID($news->news_user_id) ?>
    
    <hr />
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('form/_update', [
        'model' => $news,
        'status_publish' => $status_publish,
        'notice' => $notice,
        'type_notice' => $type_notice,
        'rubrics' => $rubrics,
        'houses' => $houses,
        'parnters' => $parnters,
        'docs' => $docs,]) ?>
    
</div>