<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Response;
    use yii\helpers\ArrayHelper;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\Services;
    use app\models\CategoryServices;
    use app\models\PaidServices;
    use app\modules\clients\models\_searchForm\searchInPaidServices;

/**
 * Платные заявки
 */
class PaidServicesController extends AppClientsController {
    
    
    /*
     * Страница "История услуг"
     * @param ActiveQuery Все платные услуги для текущего пользователя
     * @param intereg $accoint_id Значение ID лицевого счета из глобального dropDownList (хеддер)
     */
    public function actionIndex() {
        
        
        $accoint_id = $this->_choosing;
        
        // Загружаем модель добавления новой заявки
        $new_order = new PaidServices([
            'scenario' => PaidServices::SCENARIO_ADD_SERVICE,
        ]);
        
        if ($new_order->load(Yii::$app->request->post())) {

            if ($new_order->addOrder($accoint_id)) {
//                return $this->refresh();
//                var_dump($new_order->errors);
//                die();
            }
//            echo '<pre>';
//            var_dump($new_order->errors);
//            die();            
        }
        
        // Получаем список все платных заявок
        $pay_services = Services::getPayServices();
        
        // получаем список всех платных заявок
        $name_services_array = ArrayHelper::map($pay_services, 'services_id', 'services_name');
        
        return $this->render('index', ['new_order' => $new_order, 'pay_services' => $pay_services, 'name_services_array' => $name_services_array]);
        
        
    }
    
    /*
     * Страница "Заказать слугу"
     */
    public function actionOrderServices() {
        
        $this->permisionUser();
        $account_id = $this->_choosing;
        
        $all_orders = PaidServices::getOrderByUder($account_id);
        
        return $this->render('order-services', ['all_orders' => $all_orders]);
        
    }
    
    /*
     * Метод переключения текущего лицевого счета
     * dropDownList в хеддере
     */
    public function actionFilterByAccount($account_id) {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        /*
         * Проверяем на актуальность параметр ID лицевого счета
         * Если лицевой счет не входит в список лицевых счетов пользователя
         * Кидаем исключение
         */
        if (!is_numeric($account_id) || !ArrayHelper::keyExists($account_id, $this->_list)) {
            return ['status' => false];
        }
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            $all_orders = PaidServices::getOrderByUder($account_id);
            
            $data = $this->renderAjax('data/grid', ['all_orders' => $all_orders]);
            
            return ['status' => true, 'data' => $data];
            
        }
        
        return ['status' => false];
    }
    
    /*
     * Поиск заявок по исполнителю
     */
    public function actionSearchBySpecialist() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $value = Yii::$app->request->post('searchValue');
        $account = Yii::$app->request->post('accountId');
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            // Загружаем модель поиска
            $model = new searchInPaidServices();
            
            $all_orders = $model->search($value, $account);
            
            $data = $this->renderAjax('data/grid', ['all_orders' => $all_orders]);
            
            return ['status' => true, 'data' => $data];
            
        }
        
        return ['status' => false];
        
    }
        
}
