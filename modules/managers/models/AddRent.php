<?php

    namespace app\modules\managers\models;
    use yii\base\Model;
    use Yii;
    // use app\models\User;
    use app\models\Rents;
    use app\models\PersonalAccount;

/**
 * Новый арендатора
 */
class AddRent extends Model {
   
    const SCENARIO_AJAX_VALIDATION = 'add new rent';
    
    public $rents_surname;
    public $rents_name;
    public $rents_second_name;
    public $rents_mobile;
    public $rents_email;
    public $password;
    public $account_id;
    public $client_id;
    
    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [[
                'account_id', 
                'client_id', 
                'rents_surname', 'rents_name', 'rents_second_name', 
                'rents_mobile', 
                'rents_email', 
                'password'], 'required'],
            
            [['rents_surname', 'rents_name', 'rents_second_name'], 'filter', 'filter' => 'trim'],
            [['rents_surname', 'rents_name', 'rents_second_name'], 'string', 'min' => 3, 'max' => 50],
            [
                ['rents_surname', 'rents_name', 'rents_second_name'], 
                'match', 
                'pattern' => '/^[А-Яа-я\ \-]+$/iu', 
                'message' => 'Поле "{attribute}" может содержать только буквы русского алфавита, и знак "-"',
            ],
            
            [
                'rents_mobile', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
            ],
            ['rents_mobile', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i'],
            
            [
                'rents_email', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Данный электронный адрес уже используется в системе',
            ],
            
            ['rents_email', 'string', 'min' => 5, 'max' => 150],
            ['rents_email', 'email'],
            
            ['rents_email', 'match',
                'pattern' => '/^[A-Za-z0-9\_\-\@\.]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
            ],            
            
            ['password', 'string', 'min' => 6, 'max' => 12],
            ['password', 
                'match', 
                'pattern' => '/^[A-Za-z0-9\_\-]+$/iu', 
                'message' => 'Поле "{attribute}" может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
            ],
            
        ];
    }
    
    public function addNewRent() {
        
        $account = PersonalAccount::findByAccountID($this->account_id);
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->validate() && $account !== null) {

                $rent = new Rents();

                $rent->rents_name = $this->rents_name;
                $rent->rents_second_name = $this->rents_second_name;
                $rent->rents_surname = $this->rents_surname;
                $rent->rents_mobile = $this->rents_mobile;
                $rent->rents_clients_id = $this->client_id;
                $rent->isActive = Rents::STATUS_ENABLED;
                
                if(!$rent->save()) {
                    throw new \yii\db\Exception('Ошибка сохранения арендатора. Ошибка: ' . join(', ', $add_rent->getFirstErrors()));
                }

                $user = new User();
                $user->user_login = $account->account_number . 'r';
                $user->user_password = Yii::$app->security->generatePasswordHash($this->password);
                $user->user_email = $this->rents_email;
                $user->user_mobile = $this->rents_mobile;
                $user->status = User::STATUS_ENABLED;
                $user->link('rent', $rent);
                
                if(!$user->save()) {
                    throw new \yii\db\Exception('Ошибка сохранения арендатора. Ошибка: ' . join(', ', $add_rent->getFirstErrors()));
                }               
                
                $account->link('rent', $rent);
                
            }
            $transaction->commit();
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
        
    }
    
    public function attributeLabels() {
        return [
            'rents_surname' => 'Фамилия арендатора',
            'rents_name' => 'Имя арендатора',
            'rents_second_name' => 'Отчество арендатора',
            'rents_mobile' => 'Мобильный телефон',
            'rents_email' => 'Электронная почта',
            'password' => 'Пароль',
            'account_id' => 'Лицевой счет',
            'client_id' => 'ID собственника',
        ];
    }
    
    
}
