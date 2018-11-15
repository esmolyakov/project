<?php

    namespace app\modules\clients\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\SmsOperations;
    use app\models\User;

/**
 *  Смена пароля пользователя, ввод смс кода
 */
class SMSForm extends Model {
    
    public $sms_code;
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
            ['sms_code', 'required', 'message' => 'Заполните код'],
            ['sms_code', 'integer', 'min' => 10000, 'max' => 99999 ],
        ];
    }
    
    /*
     * Проверка валидации введенного смс кода и существующим в бд
     */
    public function afterValidate() {
        
        // Существующая запись по введенному смс коду
        $record = SmsOperations::findBySMSCode($this->sms_code);
        // Поиск запроса на смену пароля по ID текущего пользователя и типу операции
        $_record = SmsOperations::findByTypeOperation(SmsOperations::TYPE_CHANGE_PASSWORD);
        
        if (Yii::$app->request->cookies->has('_time')) {
            $_record->delete(false);
            $this->addError('sms_code', 'Время действия кода истекло');
            return true;
        } elseif ($record == null) {
            $this->addError('sms_code', 'Введённый код неверен');
            return true;            
        }
        
        parent::afterValidate();
    }
    
    /*
     * Организация смены пароля пользователя
     * Если валидация введенного смс кода успешна, то куку по смс операции удаляем
     */
    public function changePassword() {
        
        $record = SmsOperations::findBySMSCode($this->sms_code);
        
        if ($this->validate()) {
            $user = $this->_user;
            $user->user_password = $record->value;
            $user->save();
            $record->delete(false);
            Yii::$app->response->cookies->remove('_time');
            return true;

        }
        return false;
    }
    
    public function attributeLabels() {
        return [
            'sms_code' => 'Код из СМС'
        ];
    }
    
}
