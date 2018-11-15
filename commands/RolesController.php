<?php

    namespace app\commands;
    use Yii;
    use yii\console\Controller;
    use app\models\User;
    use yii\console\Exception;
    use yii\helpers\ArrayHelper;

/**
 * Консольный контроллер для назначения роли пользователю
 */
class RolesController extends Controller {
    
    /*
     * Назначение роли пользователю
     */
    public function actionAssign() {
        
        // В консоле выводим сообщение, укажите имя пользователя
        $username = $this->prompt('Write username: ', ['required' => true]);
        
        // Проверяем существование указанного пользователя
        $user_info = $this->findUser($username);
        
        // Формируем список доступных ролей
        $role_name = $this->select('Role: ', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));
        
        $auth_manager = Yii::$app->getAuthManager();
        $role = $auth_manager->getRole($role_name);
        
        // Выбранную роль назвачаем указанному пользователю
        $auth_manager->assign($role, $user_info->id);
        
        // Выводим сообщение в консоле об успешном назначении роли пользователю
        $this->stdout('Role was assigned for the user.' . PHP_EOL);
        
    }
    
    /*
     * Снять роль с выбранного пользователя
     */
    public function actionRevoke() {
        
        $username = $this->prompt('Write username ', ['required' => true]);
        $user_info = $this->findUser($username);
        
        $role_name = $this->select('Role: ', ArrayHelper::merge(
                ['all' => 'All Roles'], 
                ArrayHelper::map(Yii::$app->authManager->getRolesByUser($user_info->id), 'name', 'description')));
        
        $auth_manager = Yii::$app->getAuthManager();

        // С указанного пользователя снимаем все роли, или конкрено указанную
        if ($role_name == 'all') {
            $auth_manager->removeAll($user_info->id);
        } else {
            $role = $auth_manager->getRole($role_name);
            $auth_manager->revoke($role, $user_info->id);
        }
        
        // Выводим в консоле сообщение об успешном снятии роли с пользователя
        $this->stdout('Selected role(s) was revoked from the user.' . PHP_EOL);
        
    }
    
    /*
     * Поиск пользователя по указанному умени
     */
    public function findUser($username) {
        
        $model = User::findOne(['user_login' => $username]);
        
        if ($model == null) {
            throw new Exception('Username ' . $username . 'not found.');
        }
        
        return $model;
        
    }
    
}
