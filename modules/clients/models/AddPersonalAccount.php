<?php
    namespace app\modules\clients\models;
    use Yii;
    use yii\base\Model;
    use app\models\PersonalAccount;
    use app\models\User;
    use app\models\Rents;

/**
 * Добавление лицевого счета
 */
class AddPersonalAccount extends Model {
    
    // Номер лицевого счета
    public $account_number;
    // Сумма предыдущей квитанции
    public $account_last_sum;
    // Площадь квартиры
    public $square_flat;

    // Управляющая компания
    public $account_organization_id;
    
    // Собственник - Фамилия
    public $account_client_surname;
    // Собственник - Имя
    public $account_client_name;
    // Собственник - Отчество    
    public $account_client_secondname;
    // Собственник - Наличие арендатора
    public $is_rent;

/* 
    // Арендатор - Фамилия
    public $rent_surname;
    // Арендатор - Имя
    public $rent_name;
    // Арендатор - Отчество
    public $rent_secondname;
    // Арендатор - Контактный телефон
    public $rent_phone;
    // Арендатор - Email
    public $rent_email;
    // Арендатор - Пароль
    public $rent_password;
*/    
    // Квартира
    public $flat;


    public function rules() {
        return [
            [[
                'account_number', 'account_organization_id', 
                'account_last_sum', 'square_flat',
                // 'account_client_surname', 'account_client_name', 'account_client_secondname',
                'flat'], 'required'],
            
            ['account_last_sum', 'string', 'min' => 2, 'max' => 7],
            
            ['square_flat', 'string', 'min' => 2, 'max' => 5],
            
            ['flat', 'integer'],
            
            ['account_number', 'string', 'min' => 11, 'max' => 11],
            ['account_number', 'checkPersonalAccount'],
            
            ['is_rent', 'boolean'],
            
        ];
    }
    
    /*
     * Валидация номера лицевого счета
     * Если указанный лицевой счет существует в БД и он закреплен за собственником - то он считается активным
     */
    public function checkPersonalAccount() {
        $personal_account = PersonalAccount::find()
                ->andWhere(['account_number' => $this->account_number])
                ->andWhere(['not', ['personal_clients_id' => null]])
                ->asArray()
                ->one();
        if ($personal_account) {
            $errorMsg = 'Указанный лицевой счет уже используется';
            $this->addError('account_number', $errorMsg);
        }
    }
    
    /*
     * Метод добавления нового лицевого счета
     */
    public function saveAccountChekRent($_rent) {

        $connection = Yii::$app->db;
        
        $transaction = $connection->beginTransaction();
        
        try {
            
            $id_client = User::find()
                ->andWhere(['user_id' => Yii::$app->user->identity->user_id])
                ->asArray()
                ->one();
            
            $new_account = new PersonalAccount();
            $new_account->account_number = $this->account_number;
            $new_account->account_organization_id = $this->account_organization_id;
            $new_account->account_balance = $this->account_last_sum;
            $new_account->personal_clients_id = $id_client['user_client_id'];
            $new_account->isActive = PersonalAccount::STATUS_ENABLED;
            
            /*
             * Если при создании нового лицевого счета, арендатор выбран из списка, то
             * Пользователь: разблокировать учетную запись арендатора, сменить логин в соответствии с новым лицевым счетом
             * Арендатор: сменить статус - как активен, устанавливаем взять с Собственником
             * -
             */
            if (!empty($this->account_rent)) {
                $new_account->personal_rent_id = $this->account_rent;
                
                $user_rent = User::find()
                        ->andWhere(['user_rent_id' => $this->account_rent])
                        ->one();
                $user_rent->status = User::STATUS_ENABLED;
                $user_rent->user_login = $this->account_number . 'r';
                $user_rent->save(false);
                
                $rent_info = Rents::find()
                        ->andWhere(['rents_id' => $this->account_rent])
                        ->one();
                $rent_info->isActive = Rents::STATUS_ENABLED;
                $rent_info->rents_clients_id = $id_client['user_client_id'];
                $rent_info->save(false);
                
            } else {
                $new_account->personal_rent_id = (!empty($_rent)) ? $_rent['rent'] : null;
            }
            
            $new_account->personal_house_id = $this->flat;
            
            if (!$new_account->save()) {
                throw new \yii\db\Exception('Ошибка сохранения лицевого счета. Ошибка: ' . join(', ', $new_account->getFirstErrors()));
            }
            
            $transaction->commit();
        }
        catch (Exception $e) {
            $transaction->rollBack();
            $e->getMessage();
        }
    }
    
    
    public function attributeLabels() {
        return [
            'account_number' => 'Номер лицевого счета',
            'account_organization_id' => 'Управляющая организация',
            'account_last_sum' => 'Сумма последней квитанции',
            'square_flat' => 'Площадь квартиры',
            
            'account_client_surname' => 'Фамилия собственника', 
            'account_client_name' => 'Имя собственника', 
            'account_client_secondname' => 'Отчество собственника',
            
            'is_rent' => 'Арендатор',
            'account_rent' => 'Выбрать арендатора, закрепленного за собственником',
            
            'flat' => 'Адрес',
        ];
    }
    
    
   
}
