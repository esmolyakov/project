<?php

    namespace app\modules\clients\models;
    use Yii;
    use yii\base\Model;
    use app\models\User;
    use app\models\SmsOperations;

/**
 * Смена пароля учетной записи
 * 
 * @param string $current_password Текущий пароль
 * @param string $new_password Новый пароль
 * @param string $new_password Новый пароль повторно
 * @param array $_user Данные текущего пользователя
 */
class ChangePasswordForm extends Model {
    
    public $current_password;
    public $new_password;
    public $new_password_repeat;
    
    private $_user;
    
    public function __construct(User $user, $config = []) {
        
        $this->_user = $user;
        parent::__construct($config);
        
    }
    
    /*
     * Правила валидации
     */
    public function rules() {
        
        return [
            [['current_password', 'new_password', 'new_password_repeat'], 'required'],

            [['current_password', 'new_password', 'new_password_repeat'], 
                'match', 
                'pattern' => '/^[A-Za-z0-9\_\-]+$/iu', 
                'message' => 'Пароль может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
            ],            
            
            [['current_password', 'new_password', 'new_password_repeat'], 'string', 'min' => 6, 'max' => 12],
            ['new_password', 'compare', 'compareAttribute' => 'new_password_repeat'],
            ['current_password', 'checkCurrentPassword'],
        ];        
    }
    
    /*
     * Валидатор проверки текущего и введенного пароля
     */
    public function checkCurrentPassword($attribute, $param) {
        
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Текущий и введенный пароли не совпадают');
            }
        }
    }
    
    /*
     * Первый этап смены пароля
     * После прохождения валидации создаем запись в таблице "СМС операции"
     * Присваиваем операции статус "Смена пароля"
     */
    public function checkPassword() {
        
        if ($this->validate()) {
            $sms_model = new SmsOperations();
            $sms_model->operations_type = SmsOperations::TYPE_CHANGE_PASSWORD;
            $sms_model->user_id = Yii::$app->user->identity->id;
            $sms_model->sms_code = mt_rand(10000, 99999);
            $sms_model->date_registration = time();
            $sms_model->value = Yii::$app->security->generatePasswordHash($this->new_password);
            $sms_model->save(false);
            return true;
        }
        return false;
    }
    
    /*
     * Метки для полей
     */
    public function attributeLabels() {
        return [
            'current_password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
            'new_password_repeat' => 'Новый пароль повторно',
        ];
    }
    
    
    
}
