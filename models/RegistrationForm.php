<?php

    namespace app\models;
    use yii\base\Model;
    use Yii;
    use app\models\PersonalAccount;
    use app\models\Clients;    
    

/**
 * Форма регистрации пользователя
 */
class RegistrationForm extends Model {

    public $username;
    public $password;
    public $password_repeat;
    public $last_sum;
    public $square;
    public $mobile_phone;
    public $email;
    
    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [[
                'username', 'password',
                'last_sum', 'square',
                'mobile_phone', 'email',
                'password_repeat'], 'required'
            ],
            
            [[
                'username', 
                'last_sum', 'square',
                'mobile_phone', 'email'], 'filter', 'filter' => 'trim'],
            
            [['username'], 'string', 'min' => 11, 'max' => 11],
            
            // Проверка введенного логина на уникалность в таблице Пользователи
            [
                'username', 'unique', 
                'targetClass' => User::className(), 
                'targetAttribute' => 'user_login', 
                'message' => 'Пользователь с введенным лицевым счетом в системе уже зарегистрирован'
            ],
            
            // Проверка введенного email на уникальность
            [
                'email', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Пользователь с введенным элетронным аресом в системе уже зарегистрирован'
            ],
            
            // Проверка введенного номера телефона на уникальность
            [
                'mobile_phone', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
            ],
            
            ['mobile_phone', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i'],
            
            ['username', 'checkPersonalAccount'],
            
            ['password', 'compare', 'message' => 'Указанные пароли не совпадают!'],
            [['password_repeat'], 'string', 'min' => 6, 'max' => 12],
            
            ['email', 'email'],
            
        ];
    }
    
    /*
     * Проверка валидации введенного лицевого счета при регистрации
     * Если по введенному лицевому счету запись на найдена - выбросить исключение
     */
    public function checkPersonalAccount() {
        $personalAccount = PersonalAccount::find()->andWhere(['account_number' => $this->username])->select('account_number')->one();
        if ($personalAccount == null) {
            $errorMsg = 'Указанный лицевой счет в системе не существует';
            $this->addError('username', $errorMsg);
        }
    }
    
    /*
     * Метод описывает первый шаг рагистрации пользователя
     */
    public function registration($data) {
        
        if (count($data) != 6 || $data == null) {
            return false;
        }
        
        $model = new User();
        $model->user_login = $data['account'];
        $model->user_password = Yii::$app->security->generatePasswordHash($data['password']);
        $model->user_email = $data['email'];
        $model->user_mobile = $data['phone'];

        // Связываем таблицы Пользователь и Собственник
        $model->setClientByPhone($data['account']);
            
        // Новый пользователь получает статус без доступа в систему
        $model->status = User::STATUS_DISABLED;
        // Для нового пользователя генерируем ключ, для отправки на почту (Для подтверждения email)
        $model->generateEmailConfirmToken();
        // По умолчанию включаем email оповещение
        $model->user_check_email = true;
            
        if ($model->save()) {
            $this->sendEmail('EmailConfirm', 'Подтверждение регистрации', ['user' => $model]);
        }
        
        // Дропаем сессию в случае успешной регистрации нового пользователя
        Yii::$app->session->removeAll();
        
        return true;
    }
    
    /*
     * Отправка на электронный адрес ссылки на поддтверждение регистрации
     */
    public function sendEmail($view, $subject, $params = []) {
        $message = Yii::$app->mailer->compose(['html' => 'views/' . $view], $params)
                ->setFrom('email-confirm@site.com')
                ->setTo($this->email)
                ->setSubject($subject)
                ->send();
        return $message;
    }
    
    
    /*
     * Метки для полей формы
     */
    public function attributeLabels() {
        return [
            'username' => 'Номер лицевого счета',
            'last_sum' => 'Сумма предыдущей квитации',
            'square' => 'Площадь квартиры',
            'mobile_phone' => 'Мобильный телефон',
            'email' => 'Электронная почта',
            'password_repeat' => 'Пароль',
            'password' => 'Повторите введенный пароль',
        ];
    }

}
