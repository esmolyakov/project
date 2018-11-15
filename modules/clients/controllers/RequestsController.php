<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\data\ActiveDataProvider;
    use yii\web\UploadedFile;
    use yii\helpers\ArrayHelper;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\Requests;
    use app\models\Houses;
    use app\modules\clients\models\_searchForm\FilterStatusRequest;
    use app\models\CommentsToRequest;
    use app\models\Image;
    use app\models\TypeRequests;
    use app\models\StatusRequest;

/**
 * Заявки
 */
class RequestsController extends AppClientsController
{
    
    /**
     * Главная страница
     * 
     * @param array $type_requests Массив всех видом зявок
     * @param array $status_requests Пользовательские статусы заявок
     * @param integer $accoint_id Значение ID лицевого счета из глобального dropDownList (хеддер)
     */
    public function actionIndex() {
        
        $accoint_id = $this->_choosing;
        
        $type_requests = TypeRequests::getTypeNameArray();
        $status_requests = StatusRequest::getUserStatusRequests();
       
        // В датапровайдер собираем все заявки по текущему пользователю
        $all_requests = new ActiveDataProvider([
            'query' => Requests::findByAccountID($accoint_id),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15,
            ]
        ]);

        /*
         * Определяем модель добавления новой заявки
         * Если данные получены и провалидированы сохраняем заявку
         */
        
        $model = new Requests([
                'scenario' => Requests::SCENARIO_ADD_REQUEST,
            ]);
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->addRequest($accoint_id)) {
                $model->gallery = UploadedFile::getInstances($model, 'gallery');
                $model->uploadGallery();
            }
            return $this->refresh();
        }

        return $this->render('index', [
            'all_requests' => $all_requests,
            'type_requests' => $type_requests,
            'status_requests' => $status_requests,
            'model' => $model,
            'model_filter' => $model_filter,
        ]);
    }
    
    /*
     * Страница отдельной заявки
     */    
    public function actionViewRequest($request_numder) {
                
        // Ищем заявку по уникальному номеру
        $request_info = Requests::findRequestByIdent($request_numder);
        
        /*
         * Если заявка не найдена или передан номер заявки не принадлежащий пользователю, 
         * кидаем исключение
         */
        if ($request_info === null || !ArrayHelper::keyExists($request_info['requests_account_id'], $this->_list)) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        // Получаем прикрепленные к заявке файлы
        $images = Image::find()->andWhere(['itemId' => $request_info['requests_id']])->all();
        
        /*
         * Для комментариев к заявке
         */
        $comments = new CommentsToRequest([
            'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
        ]);
        $comments_find = CommentsToRequest::findCommentsById($request_info['requests_id']);        
        
        /*
         * Загружаем модель для добавления комментрария к задаче
         * Pjax
         */
        if (Yii::$app->request->isPjax) {
            $model = new CommentsToRequest([
                'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
            ]);        
            if ($model->load(Yii::$app->request->post())) {
                $model->sendComment($request_info['requests_id']);
            }
        }
        
        return $this->render('view-request', [
            'request_info' => $request_info, 
            // 'user_house' => $user_house, 
            'all_images' => $images,
            'comments' => $comments,
            'comments_find' => $comments_find,
        ]);
    }
    
    /*
     * Сортировка заявок по
     *      @param integer $account_id ID лицевого счета, 
     *      @param integer $status ID статус заявки
     */
    public function actionFilterByTypeRequest($status) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!is_numeric($account_id) && !is_numeric($status)) {
            return ['status' => false];
        }
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $model_filter = new FilterStatusRequest();
            $all_requests = $model_filter->searchRequest($status);
            return $this->renderPartial('data/grid', ['all_requests' => $all_requests, 'status' => $accoint_id]);
        }
        
        return ['status' => false];
    }
    
    /*
     * Обработка Ajax запроса на добавления оценки для заявки
     */
    public function actionAddScoreRequest() {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        // Получаем с пост запроса оценку и ID заявки
        $score = Yii::$app->request->post('score');
        $request_id = Yii::$app->request->post('request_id');
        
        // Проверяем на корректность пришедшие данные
        if (!is_numeric($score) || !is_numeric($score)) {
            return ['status' => false];
        }

        // Если пришел ajax запрос
        if ($score && $request_id && Yii::$app->request->isAjax) {
            $request = Requests::findByID($request_id);
            // Вызываем метод добавления оценки из модели
            $request->addGrade($score);
            return ['status' => true];
        }
        
        return ['status' => false];
        
    }

 }
