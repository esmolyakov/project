<?php

    namespace app\modules\managers\components;
    use yii\base\BaseObject;
    use yii\base\InvalidConfigException;
    use Yii;
    use yii\helpers\ArrayHelper;
    use app\models\User;

/* 
 * Профиль пользователя
 * 
 * Данный компоненнт предназвачен для получения полной информации о текущем пользователе
 */

class UserProfileCompany extends BaseObject {
    
    public $_user;
    public $_user_id;
    public $_model;

    public function init() {
        
        $this->_user_id = Yii::$app->user->identity->id;
        $this->_model = User::findByUser($this->_user_id);
        
        if ($this->_model == null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        return $this->info();
    }
    
    public function info() {
        
        if ($this->_user_id == null) {
            throw new InvalidConfigException('Ошибка в передаче аргументов. При вызове компонента не был задан ID пользователя');
        }
        
        if (Yii::$app->user->can('administrator')) {
            $info = (new \yii\db\Query)
                    ->select('e.employers_id as employer_id, '
                        . 'e.employers_name as name, e.employers_second_name as second_name, e.employers_surname as surname, '
                        . 'd.departments_name as departments_name, '
                        . 'u.user_id as user_id, u.user_login as login, '
                        . 'u.user_email as email, u.user_photo as photo, '
                        . 'u.user_mobile as mobile, '
                        . 'u.created_at as date_created , u.last_login as last_login, '
                        . 'u.status as status')
                    ->from('user as u')
                    ->join('LEFT JOIN', 'employers as e', 'u.user_employer_id = e.employers_id')
                    ->join('LEFT JOIN', 'departments as d', 'e.employers_department_id = d.departments_id')
                    ->where(['u.user_id' => $this->_user_id])
                    ->one();
            
            return $this->_user = $info;
            
        };        
    }
    
    /*
     * Метод получения информации о текущем пользователе
     */
    public function getUserInfo() {
        
        return $this->_user['email'];
        
    }
    
    /*
     * ID пользователя
     */
    public function getUserID() {
        return $this->_user['user_id'];
    }
    
    /*
     * Имя пользователя
     */
    public function getUsername() {
        return $this->_user['login'];
    }
    
    /*
     * Электронная почта пользоватедя
     */
    public function getEmail() {
        return $this->_user['email'];
    }
    
    /*
     * Аватар пользователя
     */
    public function getPhoto() {
        if (empty($this->_user['photo'])) {
            return Yii::getAlias('@web') . '/images/no-avatar.jpg';
        }
        return Yii::getAlias('@web') . $this->_user['photo'];
    }
    
    /*
     * Дата регистрации
     */
    public function getDateRegister() {
        return $this->_user['date_created'];
    }
    
    /*
     * Дата последнего логина
     */
    public function getLastLogin() {
        if (empty($this->_user['last_login'])) {
            return time();
        }
        return $this->_user['last_login'];
    }
    
    /*
     * Статус учетной записи пользователя
     */
    public function getStatus() {
        return ArrayHelper::getValue(User::arrayUserStatus(), $this->_user['status']);
    }
    
    /*
     * Получить роль пользователя
     */
    public function getRole() {
        if (Yii::$app->user->can('administrator')) {
            return Yii::$app->authManager->getRole('administrator')->description;
        } elseif (Yii::$app->user->can('dispatcher')) {
            return Yii::$app->authManager->getRole('dispatcher')->description;
        } elseif (Yii::$app->user->can('specialist')) {
            return Yii::$app->authManager->getRole('specialist')->description;
        }
        return 'Роль ползователя не определена';
    }
    
    /*
     * ID Собственника/Арендатора
     */
    public function getEmployerID() {
        return $this->_user['employer_id'];
    }
    
    /*
     * Имя
     */
    public function getName() {
        return $this->_user['name'];
    }

    /*
     * Фамилия
     */
    public function getSurname() {
        return $this->_user['surname'];
    }    

    /*
     * Фамилия
     */
    public function getSecondName() {
        return $this->_user['second_name'];
    }       
    
    /*
     * Фамилия имя отчество Сотрудника
     * Формат: Фамилия И.О.
     */
    public function getFullNameEmployer() {
        $_name = mb_substr($this->_user['name'], 0, 1, 'UTF-8');
        $_second_name = mb_substr($this->_user['second_name'], 0, 1, 'UTF-8');
        return $this->_user['surname'] . ' ' . $_name . '. ' . $_second_name . '.';
    }
    
    /*
     * Мобальный телефон Сотрудника
     */
    public function getMobile() {
        return $this->_user['mobile'];
    }

    public function getDepartment() {
        return $this->_user['departments_name'];
    }
        
}
