<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

class SmsOperations extends ActiveRecord
{

    const TYPE_CHANGE_PASSWORD = 1;
    
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'sms_operations';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['operations_type', 'user_id', 'sms_code', 'date_registration'], 'required'],
            [['operations_type', 'user_id', 'sms_code', 'date_registration', 'status'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }
    
    /*
     * Проверка наличия записи на запрос "смена пароля"
     */
    public static function findByUserIDAndType($user_id, $type_id) {
        
        $array = self::find()
                ->where(['user_id' => $user_id, 'operations_type' => $type_id])
                ->asArray()
                ->one();
        
        return $array == null ? false : true; 
        
    }
    
    /*
     * Проверка наличия записи по СМС коду
     */
    public static function findBySMSCode($sms_code) {
        
        $user_id = Yii::$app->user->identity->id;
        
        return $array = self::find()
                ->where(['user_id' => $user_id, 'sms_code' => $sms_code, 'status' => false])
                ->one();
        
    }
    
    /*
     * Проверка наличия записи по ID пользователя и типу операции
     */
    public static function findByTypeOperation($type_operation) {
        
        $user_id = Yii::$app->user->identity->id;
        
        return $array = self::find()
                ->where(['user_id' => $user_id, 'operations_type' => $type_operation])
                ->one();
        
    }
    
    /*
     * Генерация нового СМС кода
     */
    public function generateNewSMSCode() {
        
        $new_code = mt_rand(10000, 99999);
        $this->sms_code = $new_code;
        return $this->save(false) ? true : false;
    }
    
    /*
     * Удаление не актуальной записи операции
     */
    public static function deleteOperation($type_operation) {
        
        $user_id = Yii::$app->user->identity->id;
        $record = self::find()
                ->andWhere(['user_id' => $user_id, 'operations_type' => $type_operation])
                ->one();
        
        if ($record !== null) {
            $record->delete();
        }
        
        return true;        
        
    }
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operations_type' => 'Operations Type',
            'user_id' => 'User ID',
            'sms_code' => 'Sms Code',
            'value' => 'Value',
            'date_registration' => 'Date Registration',
            'status' => 'Status',
        ];
    }
}
