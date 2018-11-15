<?php

    namespace app\modules\clients;
    use yii\filters\AccessControl;

/**
 * clients module definition class
 */
class ClientsModule extends \yii\base\Module
{
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
        ];
    }

    // Простаранство имен
    public $controllerNamespace = 'app\modules\clients\controllers';

    public function init() {
        parent::init();       
    }   
    
}