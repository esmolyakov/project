<?php
    namespace app\models;
    use Yii;
    use yii\base\Model;
    use yii\base\InvalidParamException;
    use app\models\User;
    use app\models\AccountToUsers;
    

/*
 * Подтверждение регистрации пользователя по ссылке присланной в письме
 * Ссылка содержит случайно сгенерированный ключ/токен
 */    
class EmailConfirmForm extends Model {
    
    public $_user;

    /*
     * При каждом объявлении класса проверяем наличие токена
     * При наличии токена осуществляем поиск пользователя по заданному токену
     * 
     */
    public function __construct($token, $config = []) {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Отсутствует код подтверждения');
        }
        $this->_user = User::findByEmailConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Неверный код подтверждения');
        }
        parent::__construct($config);
    }
 
    /*
     * Метод, который меняет статус пользователя на активный;
     * Удаляет из таблицы Пользователи токен, сформированный для подтверждение регистрации;
     * Назначает роль для зарегистрированного пользователя, Клиент
     * fwDUFcLucYsQYGswEVNtNrsFzOZnN3c0
     */
    public function confirmEmail() {
        
        $user = $this->_user;
        
        /*
         * После подтверждения пользователя по email
         * делаем связь между таблицами Пользователи и Лицевой счет
         */
        $account = PersonalAccount::findOne(['account_number' => $user->user_login]);
        $bind_model = new AccountToUsers();
        
        if ($account) {
            $bind_model->account_id = $account->account_id;
            $bind_model->user_id = $user->user_id;
            $bind_model->save(false);
        }
        
        $user->status = User::STATUS_ENABLED;
        $user->email_confirm_token = null;
        
        // Назначение роли пользователю
        $userRole = Yii::$app->authManager->getRole('clients');
        Yii::$app->authManager->assign($userRole, $user->id);
        
        return $user->save(false);
    }
    
}
?>