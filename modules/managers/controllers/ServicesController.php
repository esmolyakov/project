<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use yii\web\UploadedFile;
    use yii\data\ActiveDataProvider;
    use app\modules\managers\controllers\AppManagersController;
    use app\models\Services;
    use app\modules\managers\models\form\ServiceForm;
    use app\models\Units;
    use app\models\CategoryServices;
    use app\modules\managers\models\ServicesCost;
    use app\modules\managers\models\searchForm\searchService;

/**
 * Услуги
 */
class ServicesController extends AppManagersController {
    
    /*
     * Услуги, главная страница
     */
    public function actionIndex() {
        
        $search_model = new searchService();
        
        $services = new ActiveDataProvider([
            'query' => ServicesCost::getAllServices(),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15,
            ],
        ]);
        
        return $this->render('index', [
            'services' => $services,
            'search_model' => $search_model]);
    }
    
    /*
     * Новая услуга
     */
    public function actionCreate() {
        
        $model = new ServiceForm();
        $service_categories = CategoryServices::getCategoryNameArray();
        $service_types = Services::getTypeNameArray();
        $units = Units::getUnitsArray();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $file = UploadedFile::getInstance($model, 'service_image');
            $model->service_image = $file;
            $service_id = $model->save($file);
            if ($service_id) {
                Yii::$app->session->setFlash('services-admin', [
                    'success' => true,
                    'message' => 'Новая услуга была успешно добавлена',
                ]);
                return $this->redirect(['edit-service', 'service_id' => $service_id]);
            } else {
                Yii::$app->session->setFlash('services-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'service_categories' => $service_categories,
            'service_types' => $service_types,
            'units' => $units,
        ]);
    }
    /*
     * Редактирование услуги
     */
    public function actionEditService($service_id) {
        
        $service = Services::findByID($service_id);
        
        if ($service === null) {
            throw new \yii\web\NotFoundHttpException('Вы обратились к несущствующей странице');
        }
        
        $service_categories = CategoryServices::getCategoryNameArray();
        $service_types = Services::getTypeNameArray();
        $units = Units::getUnitsArray();
        
        if ($service->load(Yii::$app->request->post())) {
            $is_valid = $service->validate();
            
            if ($is_valid) {
                $file = UploadedFile::getInstance($service, 'services_image');
                $service->uploadImage($file);
                Yii::$app->session->setFlash('services-admin', [
                    'success' => true,
                    'message' => 'Информациа об услуге успешно обновлена',
                ]);
            } else {
                Yii::$app->session->setFlash('services-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);                
            }
        }
        
        return $this->render('edit-service', [
            'service' => $service,
            'service_categories' => $service_categories,
            'service_types' => $service_types,
            'units' => $units]);
        
    }
    
    /*
     * Переключение типа услуги Услуга/Платная
     * для таблица все услуги
     */
    public function actionCheckTypeService() {
        
        $service_id = Yii::$app->request->post('serviceId');
        $type = Yii::$app->request->post('typeService');
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {            
            $service = Services::findByID($service_id);
            $service->checkType($type);
            return ['status' => true, 'type' => $type];
        }
        return ['status' => false];
    }
    
    /*
     * Запрос удаление услуги
     */
    public function actionConfirmDeleteService() {
        
        $service_id = Yii::$app->request->post('serviceId');
        
        if (Yii::$app->request->isAjax) {
            $service = Services::findByID($service_id);
            if (!$service->delete()) {
                Yii::$app->session->setFlash('services-admin', [
                    'success' => false,
                    'error' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
                ]);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('services-admin', [
                    'success' => true,
                    'message' => 'Услуга ' . $service->services_name . ' была успешно удалена',
            ]);
            return $this->redirect(['index']);
        }
        
        return $this->goHome();        
    }
    
    /*
     * Сквозной поиск по таблице услуги
     */
    public function actionSearchService() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $value = Yii::$app->request->post('searchValue');
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            // Загружаем модель поиска
            $model = new searchService();
            $result = $model->searshService($value);
            $data = $this->renderAjax('data/grid_services', ['services' => $result]);
            return ['status' => true, 'data' => $data];
        }
        return ['status' => false];        
    }
    
}
