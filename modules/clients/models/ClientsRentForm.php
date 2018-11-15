<?php

    namespace app\modules\clients\models;
    use yii\base\Model;
    use Yii;
    use app\models\Clients;
    use app\models\User;
    use app\models\Rents;
    use app\models\PersonalAccount;

/*
 * Модель для формы добавления арендатора
 */     
class ClientsRentForm extends Model {
    
    const SCENARIO_AJAX_VALIDATION = 'add new rent';
    
    public $rents_surname;
    public $rents_name;
    public $rents_second_name;
    public $rents_mobile;
    public $rents_email;
    public $password;
    public $password_repeat;

    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [[
                'rents_surname', 'rents_name', 'rents_second_name', 
                'rents_mobile', 
                'rents_email', 
                'password', 'password_repeat'], 'required', 'on' => self::SCENARIO_AJAX_VALIDATION],
            
            [['rents_surname', 'rents_name', 'rents_second_name'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_AJAX_VALIDATION],
            
            [['rents_surname', 'rents_name', 'rents_second_name'], 'string', 'min' => 3, 'max' => 50, 'on' => self::SCENARIO_AJAX_VALIDATION],
            
            [
                ['rents_surname', 'rents_name', 'rents_second_name'], 
                'match', 
                'pattern' => '/^[А-Яа-я\ \-]+$/iu', 
                'on' => self::SCENARIO_AJAX_VALIDATION,
                'message' => 'Поле "{attribute}" может содержать только буквы русского алфавита, и знак "-"',
            ],
            
            [
                'rents_mobile', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
                'on' => self::SCENARIO_AJAX_VALIDATION,
            ],
            ['rents_mobile', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i', 'on' => self::SCENARIO_AJAX_VALIDATION],
            
            [
                'rents_email', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Данный электронный адрес уже используется в системе',
                'on' => self::SCENARIO_AJAX_VALIDATION,
            ],
            ['rents_email', 'string', 'min' => 5, 'max' => 150, 'on' => self::SCENARIO_AJAX_VALIDATION],
            ['rents_email', 'email', 'on' => self::SCENARIO_AJAX_VALIDATION],
            
            ['rents_email', 'match',
                'pattern' => '/^[A-Za-z0-9\_\-\@\.]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
                'on' => self::SCENARIO_AJAX_VALIDATION,
            ],            
            
            [['password', 'password_repeat'], 'string', 'min' => 6, 'max' => 12, 'on' => self::SCENARIO_AJAX_VALIDATION],
            [['password', 'password_repeat'],
                'match', 
                'pattern' => '/^[A-Za-z0-9\_\-]+$/iu', 
                'message' => 'Поле "{attribute}" может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
                'on' => self::SCENARIO_AJAX_VALIDATION,
            ],
            
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Указанные пароли не совпадают!', 'on' => self::SCENARIO_AJAX_VALIDATION,],
            
        ];
    }
    
    /*
     * Метод сохранения нового арендатора через форму "Добавить лицевой счет"
     * Для арендатора создаем новую учетную запись
     * Новому арендатору присваиваем статус - Активный
     */
    public function saveRentToUser($client, $account) {
        
        if (!$this->validate()) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            $account_info = PersonalAccount::findOne(['account_id' => $account]);
            
            $add_rent = new Rents();
            $add_rent->rents_name = $this->rents_name;
            $add_rent->rents_second_name = $this->rents_second_name;
            $add_rent->rents_surname = $this->rents_surname;
            $add_rent->rents_mobile = $this->rents_mobile;
            $add_rent->rents_clients_id = $client;
            $add_rent->isActive = Rents::STATUS_ENABLED;

            if(!$add_rent->save()) {
                throw new \yii\db\Exception('Ошибка сохранения арендатора. Ошибка: ' . join(', ', $add_rent->getFirstErrors()));
            }
                
            $add_user = new User();
            $add_user->user_login = $account_info->account_number . 'r';
            $add_user->user_password = Yii::$app->security->generatePasswordHash($this->password);
            $add_user->user_email = $this->rents_email;
            $add_user->user_mobile = $this->rents_mobile;
            $add_user->status = User::STATUS_ENABLED;
            $add_user->user_rent_id = $add_rent->rents_id;
            // Записываем связь Пользователя с Арендатором
//            $add_user->link('rent', $add_rent);
            $add_user->save();
                
            if (!$add_user->save()) {
                throw new \yii\db\Exception('Ошибка сохранения пользователя. Ошибка: ' . join(', ', $add_user->getFirstErrors()));
            }
                
            if ($account_info) {
                $account_info->personal_rent_id = $add_rent->rents_id;
                $account_info->save(false);
            }
            $transaction->commit();
            
            return ['rent' => $add_rent->rents_id];
                
        } catch (Exception $ex) {
            $transaction->rollBack();
            // $ex->getMessage();
        }
        
        
    }
        
    public function attributeLabels() {
        return [
            'rents_surname' => 'Фамилия арендатора',
            'rents_name' => 'Имя арендатора',
            'rents_second_name' => 'Отчество арендатора',
            'rents_mobile' => 'Контактный телефон арендатора',
            'rents_email' => 'Электронная почта',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
        ];
    }
    
        
}
