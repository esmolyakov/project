<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use yii\web\Response;
    use yii\helpers\ArrayHelper;
    use app\modules\clients\behaviors\checkPersonalAccount;
        
/*
 * Общий контроллер модуля Clients
 * Наследуется всеми остальными контроллерами
 */
    
class AppClientsController extends Controller {
    
    /*
     * Назначение прав доступа к модулю "Клиенты"
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['clients', 'clients_rent'],
                    ],
                ],
            ],
            'accountList' => [
                'class' => checkPersonalAccount::className(),
                '_list' => '_list',
                '_choosing' => '_choosing',
                '_value_choosing' => '_value_choosing',
            ],
        ];
    }

    public function actions() {
        return [
            'page' => [
                'class' => 'yii\web\ViewAction',
            ],
        ];
    }
    
    
    /*
     * Метод, формирования полного профиля для текущего пользователя
     */
    public function permisionUser() {
        return Yii::$app->userProfile;
    }    
    
}
