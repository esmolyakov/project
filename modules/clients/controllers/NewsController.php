<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\data\Pagination;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\News;
    use app\models\Rubrics;
    use app\models\Image;

/**
 * Новости
 */
class NewsController extends AppClientsController {
    
    /*
     * Страница просмотра отдельной новости
     */
    public function actionViewNews($slug) {
        
        $news = News::findNewsBySlug($slug);
        if ($news === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        // Получаем список прикрепленных документоы к новости
        $files = Image::getAllDocByNews($news['news_id'], 'News');
        
        return $this->render('view-news', [
            'news' => $news,
            'files' => $files,
        ]);
    }    
    
}
