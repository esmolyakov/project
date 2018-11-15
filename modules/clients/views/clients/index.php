<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;    

/*
 * Главная страница личного кабинета Собственника
 */    
$this->title ="Главная страница";
?>

<div class="row news-list">
    <?php if (isset($news) && count($news) > 0) : ?>
        <?php foreach ($news as $key => $post) : ?>

            <div class="col-4"> <div class="card news-card-preview box-shadow">
                <?= FormatHelpers::previewNewsOrVote($post['news_preview'], false) ?>

                <h5 class="news-card-preview-h">
                    <?= FormatHelpers::formatUrlNewsOrVote($post['news_title'], $post['slug']) ?>
                </h5>

                <h5 class="news-card-preview-date">
                    <?= FormatHelpers::formatDate($post['created_at'], false) ?>
                </h5>

                <div class="card-body m-0 p-0 news-card-preview-body">
                    <p class="card-text news-card-preview-text ">
                        <?= FormatHelpers::shortTextNews($post['news_text'], 30) ?>
                    </p>
                    <div class=" d-flex justify-content-around align-items-center">
                    </div>
                </div>
            </div>
            </div>
    
            <?php if (($key + 1) % 3 == 0) : ?>
                <div class="w-100"></div>
            <?php endif; ?>
        <?php endforeach; ?>    
    <?php else : ?>
         <div class="notice notice-info">
            <strong>Новости</strong> по текущему разделу новостной информации не найдено.
        </div>
    <?php endif; ?> 
</div>
