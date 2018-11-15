<?php

    namespace app\modules\managers\controllers;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use Yii;
    use app\helpers\FormatHelpers;
    use app\models\CategoryServices;
    use app\models\Services;
    use app\models\Departments;
    use app\modules\managers\models\Posts;
    use app\modules\managers\models\User;
    use app\modules\managers\models\form\RequestForm;
    use app\models\Houses;

/*
 * Общий контроллер модуля Managers
 * Наследуется всеми остальными контроллерами
 */
    
class AppManagersController extends Controller {
    
    /*
     * Назначение прав доступа к модулю "Администратор"
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['administrator'],
                    ],
                ],
            ],
        ];
    }
    
    public function permisionUser() {
        return Yii::$app->userProfileCompany;
    }
    
    /*
     * Метод получения cookies
     */
    public function actionReadCookies() {

        /*
         * Проверяем наличие выбранного дома из списка Жилых комплексов
         * Если кука есть, то получаем из нее значение номера выбранного дома
         */
        if (Yii::$app->request->cookies->has('choosingHouse')) {
            return Yii::$app->request->cookies->get('choosingHouse')->value;
        } 
        
        return false;

        
    }
    
    /*
     * Формирование зависимых списков 
     * Выбор Должности/Специализации зависит от выбора Подразделения
     */
    public function actionShowPost($departmentId) {
        
        $department_list = Departments::find()
                ->andWhere(['departments_id' => $departmentId])
                ->asArray()
                ->count();
        $post_list = Posts::find()
                ->andWhere(['posts_department_id' => $departmentId])
                ->asArray()
                ->all();
        
        if ($department_list > 0) {
            foreach ($post_list as $post) {
                echo '<option value="' . $post['posts_id'] . '">' . $post['posts_name'] . '</option>';
            }
        } else {
            echo '<option>-</option>';
        }
    }
    
    /*
     * Формирование зависимых списков
     * Выбор названия услуги зависит от ее категории
     */
    public function actionShowNameService($categoryId) {
        
        $category_list = CategoryServices::find()
                ->andWhere(['category_id' => $categoryId])
                ->asArray()
                ->count();
        
        $service_list = Services::find()
                ->andWhere(['services_category_id' => $categoryId])
                ->asArray()
                ->all();
        
        if ($category_list > 0) {
            foreach ($service_list as $service) {
                echo '<option value="' . $service['services_id'] . '">' . $service['services_name'] . '</option>';
            }
        } else {
            echo '<option>-</option>';
        }
        
    }


    public function actionShowHouses($phone) {
        
        $model = new RequestForm();
        $client_id = $model->findClientPhone($phone);
        
        $house_list = Houses::find()
                ->andWhere(['houses_client_id' => $client_id])
                ->asArray()
                ->all();

        if (!empty($client_id)) {
            foreach ($house_list as $house) {
                $full_adress = FormatHelpers::formatFullAdress(
                        $house['houses_town'], 
                        $house['houses_street'], 
                        $house['houses_number_house'], 
                        $house['houses_floor'], 
                        $house['houses_flat']) ;
                echo '<option value="' . $house['houses_id'] . '">' . $full_adress . '</option>';
            }
        } else {
            echo '<option>Адрес не найден</option>';
        }        
        
    }
    
    /*
     * Блокировать/Разблокировать Пользователя
     * В профиле пользоваетля
     */
    public function actionBlockUserInView() {
                
        $user_id = Yii::$app->request->post('userId');
        $status = Yii::$app->request->post('statusUser');
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $user_info = User::findByID($user_id);
            $user_info->blockInView($user_id, $status);
            return ['status' => $status, 'user_id' => $user_id];
        }
        
        return ['status' => false];
    }
    
}
