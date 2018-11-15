<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Rents;
    use app\models\PersonalAccount;
    use app\models\Flats;
    use app\models\User;

/**
 * Собственники
 */
class Clients extends ActiveRecord
{
    
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            
            [[
                'clients_name', 'clients_second_name', 'clients_surname', 
                'clients_mobile', 'clients_phone'], 'required'],
            
            [['clients_name', 'clients_second_name', 'clients_surname'], 'filter', 'filter' => 'trim'],
            
            [['clients_name', 'clients_second_name', 'clients_surname'], 'string', 'min' => 3, 'max' => 70],
            
            [['clients_mobile', 'clients_phone'],
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i', 
            ],
            
            [[
                'clients_name', 'clients_second_name', 'clients_surname'], 
                'match',
                'pattern' => '/^[А-Яа-яЁё\ \-]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы русского алфавита, и знак "-"',
            ],
            
            [['clients_mobile', 'clients_phone'], 'string', 'max' => 50],
            
        ];
    }
    
    /*
     * Связь с таблицей Лицевой счет
     */
    public function getPersonalAccount() {
        return $this->hasMany(PersonalAccount::className(), ['personal_clients_id' => 'clients_id']);
    }
    
    /*
     * Связь с таблицей Квартиры
     */
    public function getFlat() {
        return $this->hasMany(Flats::className(), ['flats_client_id' => 'clients_id']);
    }
    
    /*
     * Связь с таблицей Арендаторы
     */
    public function getRent() {
        return $this->hasOne(Rents::className(), ['rents_clients_id' => 'clients_id']);
    }
    
    public function getUser() {
        return $this->hasOne(User::className(), ['user_client_id' => 'clients_id']);
    }        
    
    /*
     * Получить информацию о Собственнике
     */
    public static function getInfoByClient($clients_id) {
        
        $info = (new \yii\db\Query)
                ->select('t1.clients_id as id, t1.clients_name as name, t1.clients_second_name as second_name, t1.clients_surname as surname, '
                        . 't1.clients_mobile as mobile, t1.clients_phone as phone, '
                        . 't2.account_number as account, '
                        . 't3.user_email as email')
                ->from('clients as t1')
                ->join('LEFT JOIN', 'personal_account as t2', 't1.clients_id = t2.personal_clients_id')
                ->join('LEFT JOIN', 'user as t3', 't1.clients_id = t3.user_client_id')
                ->where(['t1.clients_id' => $clients_id])
                ->one();
        
        return $info;
        
    }
    
    public static function findByUser($account_id) {
        $user = static::findOne(['clients_account_id' => $account_id]);
        return $user;
    }
    
    /*
     * Получить Собственника по ID
     */
    public static function findById($client_id) {
        return self::find()
                ->where(['clients_id' => $client_id])
                ->one();
    }
    
    public function getId() {
        return $this->clients_id;
    }
    
    public function getFullName() {
        return $this->clients_surname . ' ' .
                $this->clients_name . ' ' .
                $this->clients_second_name;
    }
    
    public function getPhone() {
        return $this->clients_mobile;
    }
    
    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'clients_id' => 'Clients ID',
            'clients_name' => 'Имя',
            'clients_second_name' => 'Отчество',
            'clients_surname' => 'Фамилия',
            'clients_mobile' => 'Мобильный телефон',
            'clients_phone' => 'Дополнительный номер телефона',
        ];
    }
}
