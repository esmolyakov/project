<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    use yii\data\ActiveDataProvider;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\PersonalAccount;
    use app\modules\clients\models\AddPersonalAccount;
    use app\models\Organizations;
    use app\modules\clients\models\ClientsRentForm;
    use app\models\Houses;
    use app\models\Counters;
    use app\modules\clients\models\form\NewAccountForm;

/**
 * Контроллер по работе с разделом "Лицевой счет"
 */
class PersonalAccountController extends AppClientsController {

    /*
     * Главная страница
     * 
     * @param integer $accoint_id Значение ID лицевого счета из глобального dropDownList (хеддер)
     * @param array $account_all Список всех лицевых счетов Собственника
     * @param array $account_info Информация по лицевому счету Собственника
     */
    public function actionIndex() {
        
        $user_info = $this->permisionUser();
        $accoint_id = $this->_choosing;
        
        $account_info = PersonalAccount::getAccountInfo($accoint_id, $user_info->clientID);
        
        // Загуржаем модель добавления нового лицевого счета
        $model = new NewAccountForm();
        
        return $this->render('index', [
            'user_info' => $user_info,
            'account_info' => $account_info,
            'model' => $model,
        ]);
        
    }
    
    /*
     * Добавление нового лицевого счета
     * 
     * @param array $all_organizations Органицация
     * @param array $all_flat Список жилой прощади, принадлежащей собственнику
     * 
     */
    public function actionShowAddForm() {
        
        if (!Yii::$app->user->can('clients')) {
            throw new NotFoundHttpException('Пользователю с учетной записью Арендатор, доступ к данной странице запрещен');
        }        
        
        $user_info = $this->permisionUser();
        $all_organizations = Organizations::getOrganizations();
        $all_flat = Houses::findByClientID($user_info->clientID);

        // Загружаем модель добавления лицевого счета
        $add_account = new AddPersonalAccount();
        // Загружаем модель добавления нового Арендатора
        $add_rent = new ClientsRentForm();
        
        return $this->render('_form/_add_account', [
            'user_info' => $user_info,
            'all_organizations' => $all_organizations,
            'all_flat' => $all_flat,
            'add_account' => $add_account,
            'add_rent' => $add_rent,
        ]);
    }    
    
    /*
     * Страница "Квитанции ЖКУ"
     * receipts of housing and public utilities (receipts-of-hapu)
     */
    public function actionReceiptsOfHapu() {
        
        $user_info = $this->permisionUser();
        
        // Получить список всех лицевых счетов пользователя        
        $account_all = $this->_list;
        
        return $this->render('receipts-of-hapu', [
            'account_all' => $account_all,
        ]);
    }
    
    /*
     * Страница "Платеж"
     */
    public function actionPayment() {
        
        return $this->render('payment');
        
    }

    /*
     * Страница "Приборы учета"
     * @param intereg $accoint_id Значение ID лицевого счета из глобального dropDownList (хеддер)
     */
    public function actionCounters() {

        $user_info = $this->permisionUser();
        $account_id = $this->_choosing;
        
        // Получаем текущую дату
        $current_date = time();        
        // Получаем номер текущего месяца
        $current_month = date('n');
        // Получаем номер текущего года
        $current_year = date('Y');
        
        $counters = new ActiveDataProvider([
            'query' => Counters::getReadingCurrent($account_id, $current_month = 9, $current_year),            
        ]);
        
        return $this->render('counters', [
            'current_date' => $current_date,
            'counters' => $counters,
        ]);
        
    }
    
    /*
     * Метод фильтра лицевых счетов
     * dropDownList
     */
    public function actionList() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $account_id = Yii::$app->request->post('accountId');
        $client_id = Yii::$app->request->post('clientId');
        
        if (Yii::$app->request->isAjax) {
            $account_info = PersonalAccount::getAccountInfo($account_id, $client_id);
            $data = $this->renderPartial('_data-filter/list', ['account_info' => $account_info]);
            return ['success' => true, 'data' => $data];
        }
        return ['success' => false];

    }
    
    /*
     * Валидация формы "Добавление нового арендатора"
     */
    public function actionValidateAddRentForm() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // Если данные пришли через пост и аякс
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            // Объявляем модель арендатор, задаем сценарий валидации для формы
            $model = new ClientsRentForm([
                'scenario' => ClientsRentForm::SCENARIO_AJAX_VALIDATION,
            ]);
            // Если модель загружена
            if ($model->load(Yii::$app->request->post())) {
                // и прошла валидацию
                if ($model->validate()) {
                    // Для Ajax запроса возвращаем стутас, ок
                    return ['status' => true];
                }
            }
            // Инваче, запросу отдаем ответ о проваленной валидации и ошибки
            return [
                'status' => false,
                'errors' => $model->errors,
            ];
        }
        return ['status' => false];
    }
    
    /*
     * Добавление нового лицевого счета
     */
    public function actionAddRecordAccount($form) {
        
        $user_info = $this->permisionUser();

        if (Yii::$app->request->isPost && $user_info->_user['client_id']) {
            
            $account_form = new AddPersonalAccount();
            $account_form->load(Yii::$app->request->post());
            $account_form->validate();
            
            $new_account = $account_form->account_number;
            
            if ($account_form->hasErrors()) {
                
                Yii::$app->session->setFlash('form', ['success' => false, 'error' => 'При отправке формы возникла ошибка, попробуйте заполнить форму заново (*) ']);
                if (Yii::$app->request->referrer) {
                    Yii::$app->response->setStatusCode(400);
                    return Yii::$app->request->isAjax ? \yii\helpers\Json::encode(['success' => false]) : $this->redirect(Yii::$app->request->referrer);
                }
                return Yii::$app->request->isAjax ? \yii\helpers\Json::encode(['success' => false, 'error' => 'Ошибка формы 1']) : $this->goHome();
            }
            
            // Заполняем массив данными о новом аренадтор
            $data_rent = Yii::$app->request->post('ClientsRentForm');
            // Проверям массив на пустоту
            if (array_filter($data_rent)) {
                // Если массив не пустой, передаем в модель Арендатор данные
                $rent_form = new ClientsRentForm($data_rent);
                
                // Выводим ошибки в случае неудачной валидации
                if (!$rent_form->validate()) {
                    Yii::$app->session->setFlash('form', ['success' => false, 'error' => 'При отправке формы возникла ошибка, попробуйте заполнить форму заново (**) ']);
                    if (Yii::$app->request->referrer) {
                        Yii::$app->response->setStatusCode(400);
                        return Yii::$app->request->isAjax ? \yii\helpers\Json::encode(['success' => false]) : $this->redirect(Yii::$app->request->referrer);                        
                    }
                    return Yii::$app->request->isAjax ? \yii\helpers\Json::encode(['success' => false]) : $this->goHome();
                    // return $rent_form->errors;
                }
                
                // Если данные прошли валидацию и успешно сохранены
                $_rent = $rent_form->saveRentToUser($data_rent, $new_account);
                if ($_rent) {
                    // Сохраняем новый лицевой счет
                    $account_form->saveAccountChekRent($_rent);
                    Yii::$app->session->setFlash('form', ['success' => true, 'message' => 'Лицевой счет создан. Созданный лицевой счет связан с новым арендатором']);
                }
                
            } else {
                $account_form->saveAccountChekRent($_rent = null);
                Yii::$app->session->setFlash('form', ['success' => true, 'message' => 'Лицевой счет создан']);
            }
            
            return $this->redirect(Yii::$app->request->referrer);            
        }
        return $this->goHome();
        
    }

    
    /*
     * Метод переключения текущего лицевого счета для страницы "Показания приборов"
     * dropDownList в хеддере
     */
    public function actionFilterByAccount($account_id) {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!is_numeric($account_id)) {
            return ['status' => false];
        }
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $counters = new ActiveDataProvider([
                'query' => Counters::getReadingCurrent($account_id, $current_month = 9, $current_year = 2018),            
            ]);
            $data = $this->renderAjax('data/grid', ['counters' => $counters]);
            return ['status' => true, 'data' => $data];
        }
        return ['status' => false];
    }
    
    /*
     * Ajax Вадилация формы в модальном окне "Добавление нового лицевого счета"
     */
    public function actionValidateAccountForm() {
        
        $model = new NewAccountForm();
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
    }
    
    public function actionCreateAccount($client_id) {
        
        $model = new NewAccountForm();
        
        if ($model->load(Yii::$app->request->post() && $model->validate())) {
            return 'here';
        }
        
    }
    
    
}
