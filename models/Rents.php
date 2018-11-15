<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;    
    use app\models\PersonalAccount;
    use app\models\User;
    use app\models\Clients;
    use app\models\Rents;
    use app\models\AccountToUsers;

/**
 * Арендатор
 */
class Rents extends ActiveRecord
{
    
    /*
     * Статусы арендатора
     * STATUS_DISABLED - арендатор не активен, не закреплен за лицевым счетом
     * STATUS_ENABLED - арендатор ативен, закреплен за лицевым счетом
     */
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    
    const SCENARIO_EDIT_VALIDATION = 'required fields';

    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'rents';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['rents_name', 'rents_second_name', 'rents_surname', 'rents_mobile'], 'required'],
            [['rents_name', 'rents_second_name', 'rents_surname'], 'filter', 'filter' => 'trim'],
            [['rents_name', 'rents_second_name', 'rents_surname'], 'string', 'min' => 3, 'max' => 50],
            [
                ['rents_name', 'rents_second_name', 'rents_surname'], 
                'match',
                'pattern' => '/^[А-Яа-я\ \-]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы русского алфавита, и знак "-"',
            ],
            
            [
                'rents_mobile', 'unique',
                'targetClass' => self::className(),
                'targetAttribute' => 'rents_mobile',
                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
                'on' => self::SCENARIO_EDIT_VALIDATION,
            ],
            
            ['rents_mobile', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i'],
            ['rents_mobile_more', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{4}\)?[\- ]?)?[\d\- ]{7,10}$/i'],
            
            ['isActive', 'boolean'],
        ];
    }
    
    /*
     * Связь с таблицей Собственники
     */
    function getClient() {
        return $this->hasOne(Clients::className(), ['clients_id' => 'rents_clients_id']);
    }
    
    public function getUser() {
        return $this->hasOne(User::className(), ['user_rent_id' => 'rents_id']);
    }
    
    public static function findByRentId($rent_id) {
        return static::find()
                ->where(['rents_id' => $rent_id])
                ->one();
    }
    
    /*
     * Найти собсвенника по ID
     */
    public static function findByRent($client_id) {
        return static::find()
                ->andWhere(['rents_clients_id' => $client_id])
                ->one();
    }
    
    /*
     * Сформировать массив арендаторов закрепленных за собственником, 
     * со статусом "не активен"
     */
    public static function findByClientID($client_id) {
        $_list = self::find()
                ->andWhere(['rents_clients_id' => $client_id])
                ->andWhere(['isActive' => self::STATUS_DISABLED])
                ->select(['rents_id', 'rents_surname', 'rents_name', 'rents_second_name'])
                ->asArray()
                ->all();
        
        return 
            ArrayHelper::map($_list, 'rents_id', function ($elem) {
                return $elem['rents_surname'] . ' ' . $elem['rents_name'] . ' ' . $elem['rents_second_name'];
            });
    } 
    
    /*
     * Проверям наличие активных арендаторов у Собственника
     */
    public static function isActiveRents($client_id) {
        return $active_rent = self::find()
                ->andWhere(['rents_clients_id' => $client_id, 'isActive' => self::STATUS_ENABLED])
                ->all();
    }

    /*
     * Проверям наличие не активных арендаторов у Собственника
     */
    public static function getNotActiveRents($client_id) {
        return $not_active_rent = self::find()
                ->andWhere(['rents_clients_id' => $client_id, 'isActive' => self::STATUS_DISABLED])
                ->with('user')
                ->all();
    }
    
    /*
     * Получить ID арендатора
     */
    public function getId() {
        return $this->rents_id;
    }
    
    /*
     * Полное имя (фамилия, имя, отчество)
     */
    public function getFullName() {
        return $this->rents_surname . ' ' .
                $this->rents_name . ' ' .
                $this->rents_second_name;
    }
    
    /*
     * Получить информацию о Собственнике
     */
    public static function getInfoByRent($rents_id) {

        $info = (new \yii\db\Query)
                ->select('t1.rents_id as id, t1.rents_name as name, t1.rents_second_name as second_name, t1.rents_surname as surname, '
                        . 't1.rents_mobile as mobile, t1.rents_mobile_more as phone, '
                        . 't2.account_number as account, '
                        . 't3.user_email as email')
                ->from('rents as t1')
                ->join('LEFT JOIN', 'personal_account as t2', 't1.rents_id = t2.personal_rent_id')
                ->join('LEFT JOIN', 'user as t3', 't1.rents_id = t3.user_rent_id')
                ->where(['t1.rents_id' => $rents_id])
                ->one();
        
        return $info;
        
    }
    
    /*
     * После удаления Арендтора из системы
     * 
     * Удалить учетную запись Арендатора
     * Снять связь между удаленным Арендатором и Лицевым счетом
     */
    public function afterDelete() {
        
        parent::afterDelete();
        $_user = User::findOne(['user_rent_id' => $this->rents_id]);
        $_account = PersonalAccount::findOne(['personal_rent_id' => $this->rents_id]);
        
        if ($_user) {
            $_user->delete();
            if ($_account) {
                $_account->personal_rent_id = null;
                $_account->save(false);
                
                Yii::$app->session->setFlash('profile', [
                    'success' => true, 
                    'message' => 'Арендатор ' . $this->fullName . ' и его учетная запись удалены с портала'
                ]);
            }
        }
    }

    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'rents_id' => 'Rents ID',
            'rents_name' => 'Имя',
            'rents_second_name' => 'Отчество',
            'rents_surname' => 'Фамилия',
            'rents_mobile' => 'Контактный телефон',
            'rents_mobile_more' => 'Дополнительный номер телефона',
        ];
    }
}
