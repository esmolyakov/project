<?php

    namespace app\models;
    use Yii;
    use yii\base\Model;
    use app\models\User;

/**
 * Форма входа в систему
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }
    
    /*
     * Метки полей для формы
     */
    public function attributeLabels() {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];        
    }

    /**
     * Проверка введенного пароля пользователем
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Данной комбинации логина и пароля не существует. Попробуйте ввести данные еще раз');
            } else if ($user && $user->status == User::STATUS_DISABLED) {
                $this->addError($attribute, 'Вы не прошли подтвержение регистрации на портале. Проверьте вашу электронную почту');
            } else if ($user && $user->status == User::STATUS_BLOCK) {
                $this->addError($attribute, 'Ваша учетная запись была заблокирована');
            }
            
        }
    }

    /**
     * Процесс авторизации пользователя
     */
    public function login()
    {
        // Есди валидация формы прошла успешно
        if ($this->validate()) {
            // Если установлен флаг "Запомнить меня"
            if ($this->rememberMe) {
                // Получаем текущего пользователя
                $user = $this->getUser();
                // Записываем в БД снегерированный ключ для аутентивикации по cookie
                $user->generateAuthKey();
                // Дата последнего входа на портал
//                $user->date_login = time();
                $user->save();
            }            
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Получить пользователя
     * Если имя пользователя найдено в БД, то разрешаем доступ к системе
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}