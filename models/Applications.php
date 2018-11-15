<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;    
    use app\models\Services;
    use app\models\PersonalAccount;
    

class Applications extends ActiveRecord
{
    
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'applications';
    }
    
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('CURRENT_TIMESTAMP')
            ]
        ];
    }    

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['applications_category_id', 'applications_service_id', 'applications_dispatcher_id', 'applications_specialist_id', 'applications_account_id', 'status', 'is_accept', 'applications_grade', 'created_at', 'updated_at'], 'integer'],
            [['applications_number'], 'string', 'max' => 16],
            [['applications_phone'], 'string', 'max' => 70],
        ];
    }
    
    /*
     * Связь с таблицей Лицевой счет
     */
    public function getAccount() {
        return $this->hasOne(PersonalAccount::className(), ['account_id' => 'applications_account_id']);
    }
    
    /*
     * Связь с таблицей услуги
     */
    public function getService() {
        return $this->hasOne(Services::className(), ['services_id' => 'services_name_services_id']);
    }
    
    public function getId() {
        return $this->applications_id;
    }
    
    public function getNumber() {
        return $this->applications_number;
    }
    
    /*
     * Поиск по номеру заявки
     */
    public static function findByNubmer($number) {
        
        $query = (new \yii\db\Query)
                ->from('applications as a')
                ->join('LEFT JOIN', 'category_services as c', 'c.category_id = a.applications_category_id')
                ->join('LEFT JOIN', 'services as s', 's.services_category_id = c.category_id')
                ->join('LEFT JOIN', 'personal_account as p', 'p.account_id = a.applications_account_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = p.personal_house_id')
                ->where(['applications_number' => $number])
                ->one();
        
        return $query;
    }
    
    /**
     * Атрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'applications_id' => 'Applications ID',
            'applications_number' => 'Applications Number',
            'applications_category_id' => 'Applications Category ID',
            'applications_service_id' => 'Applications Service ID',
            'applications_phone' => 'Applications Phone',
            'applications_dispatcher_id' => 'Applications Dispatcher ID',
            'applications_specialist_id' => 'Applications Specialist ID',
            'applications_account_id' => 'Applications Account ID',
            'status' => 'Status',
            'is_accept' => 'Is Accept',
            'applications_grade' => 'Applications Grade',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
