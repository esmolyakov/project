<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\data\ActiveDataProvider;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\TypeRequests;
    use app\models\CategoryServices;
    use app\modules\managers\models\form\RequestForm;
    use app\modules\managers\models\form\PaidRequestForm;
    use app\modules\managers\models\Requests;
    use app\modules\managers\models\PaidServices;
    use app\models\CommentsToRequest;
    use app\models\Image;
    use yii\helpers\ArrayHelper;

/**
 * Заявки
 */
class RequestsController extends AppManagersController {
    
    public $type_request = [
        'request',
        'paid-request',
    ];
    
    /*
     * Заявки, главная страница
     */
    public function actionRequests() {
        
        $model = new RequestForm();
        $type_request = TypeRequests::getTypeNameArray();
        $flat = [];
        
        $requests = new ActiveDataProvider([
            'query' => Requests::getAllRequests(),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15,
            ],
        ]);
        
        return $this->render('requests', [
            'model' => $model,
            'type_request' => $type_request,
            'flat' => $flat,
            'requests' => $requests,
        ]);
    }
    
    public function actionPaidServices() {
        
        $model = new PaidRequestForm();
        $servise_category = CategoryServices::getCategoryNameArray();
        $servise_name = [];
        $flat = [];
        
        $paid_requests = new ActiveDataProvider([
            'query' => PaidServices::getAllPaidRequests(),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15,
            ],
        ]);
        
        return $this->render('paid-services', [
            'model' => $model,
            'servise_category' => $servise_category,
            'servise_name' => $servise_name,
            'flat' => $flat,
            'paid_requests' => $paid_requests,
        ]);
    }
    
    /*
     * Просмотр и редактирование заявок
     */
    public function actionViewRequest($request_number) {
        
        $request = Requests::findRequestByIdent($request_number);
        
        if (!isset($request) && $request == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $model_comment = new CommentsToRequest([
            'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
        ]);

        /*
         * Загружаем модель для добавления комментрария к задаче
         * Pjax
         */
        if (Yii::$app->request->isPjax) {
            $model_comment = new CommentsToRequest([
                'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
            ]);        
            if ($model_comment->load(Yii::$app->request->post())) {
                $model_comment->sendComments($request['requests_id']);
            }
        }
                
        $comments_find = CommentsToRequest::getCommentByRequest($request['requests_id']);
        
        // Получаем прикрепленные к заявке файлы
        $images = Image::find()->andWhere(['itemId' => $request['requests_id']])->all();
        
        return $this->render('view-request', [
            'request' => $request,
            'model_comment' => $model_comment,
            'comments_find' => $comments_find,
            'all_images' => $images,
        ]);
    }
    
    /*
     * Просомтр и редактирование заявки, на платную услугу
     */
    public function actionViewPaidRequest($request_number) {
        
        $paid_request = PaidServices::findRequestByIdent($request_number);
        
        if (!isset($paid_request) && $paid_request == null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $model_comment = new CommentsToRequest([
            'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
        ]);

        /*
         * Загружаем модель для добавления комментрария к задаче
         * Pjax
         */
        if (Yii::$app->request->isPjax) {
            $model_comment = new CommentsToRequest([
                'scenario' => CommentsToRequest::SCENARIO_ADD_COMMENTS
            ]);        
            if ($model_comment->load(Yii::$app->request->post())) {
                $model_comment->sendComments($paid_request['id']);
            }
        }
                
        $comments_find = CommentsToRequest::getCommentByRequest($paid_request['id']);
        
        return $this->render('view-paid-request', [
            'paid_request' => $paid_request,
            'model_comment' => $model_comment,
            'comments_find' => $comments_find,            
        ]);
    }
    
    /*
     * Метод сохранения созданной заявки
     */
    public function actionCreateRequest() {
        
        $model = new RequestForm();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $number = $model->save();
            return $this->redirect(['view-paid-request', 'request_number' => $number]);
        }
    }
    
    /*
     * Метод сохранения созданной заявки на платную услугу
     */
    public function actionCreatePaidRequest() {
        
        $model = new PaidRequestForm();
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $number = $model->save();
            return $this->redirect(['view-paid-request', 'request_number' => $number]);
        }
    }    
    
    /*
     * Валидация формы в модальном окне "Создать заявку"
     * Валидация формы в модальном окне "Создать заявку на платную услугу"
     */
    public function actionValidationForm($form) {
        
        if ($form == 'new-request') {
            $model = new RequestForm();
        } elseif ($form == 'paid-request') {
            $model = new PaidRequestForm();
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
    }
    
    /*
     * Метод переключения статуса для заявки
     */
    public function actionSwitchStatusRequest() {
        
        $status = Yii::$app->request->post('statusId');
        $request_id = Yii::$app->request->post('requestId');
        
        if (Yii::$app->request->isAjax) {
            $request = Requests::findOne($request_id);
            $request->switchStatus($status);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => $request, 'status' => $status];
        }
        
        return ['success' => false];
    }
    
    /*
     * Метод переключения статуса для заявки на платную услугу
     */
    public function actionSwitchStatusPaidRequest() {
        
        $status = Yii::$app->request->post('statusId');
        $request_id = Yii::$app->request->post('requestPaidId');
        
        if (Yii::$app->request->isAjax) {
            $request = PaidServices::findOne($request_id);
            $request->switchStatus($status);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => $request, 'status' => $status, 'request_id' => $request_id];
        }
        
        return ['success' => false];
        
    }    
    
    /*
     * Назначение диспетчера для Заявок и Заявок на платные услуги
     */
    public function actionChooseDispatcher() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $request_id = Yii::$app->request->post('requestId');
        $dispatcher_id = Yii::$app->request->post('dispatcherId');
        $type_request = Yii::$app->request->post('typeRequest');
        
        // Если параметр "тип заявки" пришел не верный отправляем на главную страницу
        if (ArrayHelper::keyExists($type_request, $this->type_request)) {
            return $this->goHome();
        }
        
        if (Yii::$app->request->isAjax) {
            switch ($type_request) {
                case 'request':
                    $request = Requests::findByID($request_id);
                    $request->chooseDispatcher($dispatcher_id);
                    return ['success' => true];
                    break;
                
                case 'paid-request':
                    $paid_request = PaidServices::findOne($request_id);
                    $paid_request->chooseDispatcher($dispatcher_id);
                    return ['success' => true];
                    break;
                default:
                    return ['success' => false];
            }
            return ['success' => true];
        }
        
        return ['success' => false];
        
    }
    
    /*
     * Назначение специалиста для Заявок и Заявок на платные услуги
     */
    public function actionChooseSpecialist() {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $request_id = Yii::$app->request->post('requestId');
        $specialist_id = Yii::$app->request->post('specialistId');
        $type_request = Yii::$app->request->post('typeRequest');
        
        // Если параметр "тип заявки" пришел не верный отправляем на главную страницу
        if (ArrayHelper::keyExists($type_request, $this->type_request)) {
            return $this->goHome();
        }        
        
        if (Yii::$app->request->isAjax) {
            
            switch ($type_request) {
                case 'request':
                    $request = Requests::findByID($request_id);
                    $request->chooseSpecialist($specialist_id);
                    return ['success' => true];
                    break;
                case 'paid-request':
                    $paid_request = PaidServices::findOne($request_id);
                    $paid_request->chooseSpecialist($specialist_id);
                    return ['success' => true];
                    break;
                default:
                    return ['success' => false];                    
                    break;
            }
        }
        
        return ['success' => false];
        
    }
    
}
