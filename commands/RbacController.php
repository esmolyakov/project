<?php
    namespace app\commands;
    use yii\console\Controller;
    use Yii;


/*
 * Испольщование таблиц БД
 * 
 */    

/*
 * Инициация RBAC через консоль
 * Добавление ролей для пользователей:
 * clients - Собственник
 * clients_rent - Аредатор
 * dispatcher - Сотрудник (Диспетчер)
 * specialist - Специалист
 * administrator - Администратор
*/
    
class RbacController extends Controller {
    
    public function actionInit() {
        
        if (!$this->confirm("Данная команда удалит текущие роли и разрешения и пересоздаст исходные. Продолжть?")) {
            return self::EXIT_CODE_NORMAL;
        }
        
        $auth = Yii::$app->authManager;
        
        $auth->removeAll();

        /*
         * Роли
         */
        $clients = $auth->createRole('clients');
        $clients->description = 'Собственник';
        $auth->add($clients);
        
        $clients_rent = $auth->createRole('clients_rent');
        $clients_rent->description = 'Арендатор';
        $auth->add($clients_rent);        
        
        $dispatcher = $auth->createRole('dispatcher');
        $dispatcher->description = 'Сотрудник (Диспетчер)';
        $auth->add($dispatcher);        
        
        $specialist = $auth->createRole('specialist');
        $specialist->description = 'Специалист';
        $auth->add($specialist);
                
        $administrator = $auth->createRole('administrator');
        $administrator->description = 'Администратор';
        $auth->add($administrator);

        /*
         *  Разрешение для Собственников и Арендаторов
         */
        $addNewRent = $auth->createPermission('AddNewRent');
        $addNewRent->description = 'Добавить нового аредатора';
        $auth->add($addNewRent);

//        $viewInfoRent = $auth->createPermission('ViewInfoRent');
//        $viewInfoRent->description = 'Редактирование информации об Арендаторе (блок Контактные даныые арендатора)';
//        $auth->add($viewInfoRent);

        $vote = $auth->createPermission('Vote');
        $vote->description = 'Участие в голосовании';
        $auth->add($vote);
        
        $addNews = $auth->createPermission('AddNews');
        $addNews->description = 'Добавение новостей';
        $auth->add($addNews);
        
        //Назначаем разрешения добавлять/радактировать информацию об Арендаторе только для роли Собсвенник
        $auth->addChild($clients, $addNewRent);
        $auth->addChild($clients, $vote);
        //$auth->addChild($clients, $viewInfoRent);
        
        // Назначаем разрешения добавлять/радактировать новости только для роли Специалист(выборочно)/Администратор
        $auth->addChild($dispatcher, $addNews);
        
        
        // Администратор обладает правами всех других пользователей
        /*
        $auth->addChild($administrator, $clients);
        $auth->addChild($administrator, $clients_rent);
        $auth->addChild($administrator, $dispatcher);
        $auth->addChild($administrator, $specialist);
        $auth->addChild($administrator, $addNewRent);
        $auth->addChild($administrator, $viewInfoRent);
         */
        
        $this->stdout('here', PHP_EOL);
        
    }
}
?>

