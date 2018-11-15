<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\data\ActiveDataProvider;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\form\NewsForm;
    use app\modules\managers\models\News;
    use app\models\Rubrics;
    use app\models\Houses;
    use app\models\HousingEstates;
    use app\helpers\FormatHelpers;
    use app\modules\managers\models\HousesEstates;
    use app\models\Image;
    use app\models\Partners;

/**
 * Новости
 */
class NewsController extends AppManagersController {
    
    /*
     * Действия
     *      image-upload Загрузка изображений используемых в редакторе текста новостей
     *      file-delete Удаление изображений загруженных через редактор текста новостей
     */
    public function actions() {
        
        return [
            
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => 'http://dev.management-company/web/upload/news', // Directory URL address, where files are stored.
                'path' => '@webroot/upload/news', // Or absolute path to directory where files are stored.
            ],
            
            'file-delete' => [
                'class' => 'vova07\imperavi\actions\DeleteFileAction',
                'url' => 'http://dev.management-company/web/upload/news', // Directory URL address, where files are stored.
                'path' => '/var/www/my-site.com/dev.management-company/web/upload/news', // Or absolute path to directory where files are stored.
            ],            
         
       ]; 
        
    }    
    
    /*
     * Новости, главная страница
     */
    public function actionNews() {
        
        $all_news = new ActiveDataProvider([
            'query' => News::getAllNews($adver = false),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15,
            ],
        ]);
        
        return $this->render('news', [
            'all_news' => $all_news,
        ]);
    }
    
    /*
     * Реклама, главная страница
     */
    public function actionAdverts() {
        
        $all_adverts = new ActiveDataProvider([
            'query' => News::getAllNews($adver = true),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15,
            ],
        ]);        
        
        return $this->render('adverts', [
            'all_adverts' => $all_adverts,
        ]);
    }    
    
    /*
     * Создание публикации
     * 
     * $status_publish array Статус размещения публикации
     * $rubrics array Тип публикации
     */
    public function actionCreate() {
        
        $model = new NewsForm();
        $status_publish = News::getStatusPublish();
        $notice = News::getArrayStatusNotice();
        $type_notice = News::getNoticeType();
        $rubrics = Rubrics::getArrayRubrics();
        $houses = [];
        $parnters = Partners::getAllParnters();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Превью
            $file = UploadedFile::getInstance($model, 'preview');
            $model->preview = $file;
            // Прикрепленные файлы
            $files = UploadedFile::getInstances($model, 'files');
            $model->files = $files;
            
            $slug = $model->save($file, $files);            
            if ($slug) {
                Yii::$app->session->setFlash('news-admin', [
                    'success' => true,
                    'message' => 'Новость была успешно добавлена',
                ]);                
                return $this->redirect(['view', 'slug' => $slug]);
            } else {
                Yii::$app->session->setFlash('news-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'status_publish' => $status_publish,
            'notice' => $notice,
            'type_notice' => $type_notice,
            'rubrics' => $rubrics,
            'houses' => $houses,
            'parnters' => $parnters,
        ]);
    }
    
    /*
     * Просмотр, редактирование публикации
     */
    public function actionView($slug) {
        
        $news = News::findNewsBySlug($slug);
        
        if ($news == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $status_publish = News::getStatusPublish();
        $notice = News::getArrayStatusNotice();
        $type_notice = News::getNoticeType();
        $rubrics = Rubrics::getArrayRubrics();
        $houses = HousesEstates::getHouseOrEstate($news->news_status);
        $parnters = Partners::getAllParnters();
        
        // Получаем прикрепленные к заявке файлы
        $docs = Image::getAllDocByNews($news->news_id, $model_name = 'News');
        
        if ($news->load(Yii::$app->request->post())) {
            $is_valid = $news->validate();
            
            if ($is_valid) {
                // Превью
                $file = UploadedFile::getInstance($news, 'news_preview');
                $news->uploadImage($file);
                
                // Прикрепленные документы
                $files = UploadedFile::getInstances($news, 'files');
                $news->uploadFiles($files);
                
                Yii::$app->session->setFlash('news-admin', [
                    'success' => true,
                    'message' => 'Изменения были успешно сохранены',
                ]);
                
                return $this->redirect(Yii::$app->request->referrer);
                
            } else {
                Yii::$app->session->setFlash('news-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                
                return $this->redirect(Yii::$app->request->referrer);

            }
            
        }
        
        return $this->render('view', [
            'news' => $news,
            'status_publish' => $status_publish,
            'notice' => $notice,
            'type_notice' => $type_notice,
            'rubrics' => $rubrics,
            'houses' => $houses,
            'parnters' => $parnters,
            'docs' => $docs,
        ]);
    }
    
    /*
     * Зависимый переключатель статуса публикации
     *      Для всех
     *      Для жилого комплекса
     *      Для конкретного дома
     */
    public function actionForWhomNews($status) {
        
        // Полчаем список всех домов
        $current_house = Houses::getHousesList();
        // Получаем список всех жилых комплексов
        $housing_estates = HousingEstates::getHousingEstateList();
        
        if ($status == 0) {
            echo '<option>Для всех</option>';
        } elseif ($status == 1) {
            foreach ($housing_estates as $estate) {
                $name = FormatHelpers::formatEstateAdress($estate['estate_name'], $estate['estate_town']);
                echo '<option value="' . $estate['estate_id'] . '">' . $name . '</option>';
            }
        } elseif ($status == 2) {
            foreach ($current_house as $house) {
                $full_adress = FormatHelpers::formatFullAdress(
                        $house['estate_town'], 
                        $house['houses_street'], 
                        $house['houses_number_house'], 
                        false, false);
                echo '<option value="' . $house['houses_id'] . '">' . $full_adress . '</option>';
            }
        }
        
    }
    
    /*
     * Запрос на удаление публикации
     */
    public function actionDeleteNews() {
        
        $news_id = Yii::$app->request->post('newsId');
        $_redirect = Yii::$app->request->post('isAdvert') == 1 ? ['news/adverts'] : ['news/news'];
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $news = News::findOne($news_id);
            
            if (!$news->delete()) {
                Yii::$app->session->setFlash('news-admin', [
                    'success' => false, 
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз']);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('news-admin', [
                'success' => true, 
                'message' => 'Новость ' . $news->news_title . ' была успешно удалена']);
        }
        return $this->redirect($_redirect);        
    }
    
    /*
     * Запрос на удаление прикрепленного документа
     */
    public function actionDeleteFile() {
        
        $file_id = Yii::$app->request->post('fileId');
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $file = Image::findOne($file_id);
            if (!$file->delete()) {
                Yii::$app->session->setFlash('news-admin', [
                    'success' => false, 
                    'error' => 'Извините, при удалении документа произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз']);
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    
}
