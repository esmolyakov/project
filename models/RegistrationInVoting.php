<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

/**
 * Регистрация в голосованиии
 */
class RegistrationInVoting extends ActiveRecord
{
    /*
     * Статус участния в голосования
     */
    // Пользователь поставлен на регистрацию, Отправлено СМС
    const STATUS_DISABLED = 0; 
    // Пользователь зарегистрирован, СМС получено и введен верный код
    const STATUS_ENABLED = 1;

    /**
     * Таблица в бд
     */
    public static function tableName() {
        return 'registration_in_voting';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['voting_id', 'user_id', 'date_registration'], 'required'],
            [['voting_id', 'user_id', 'random_number', 'date_registration', 'status'], 'integer'],
            [['voting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Voting::className(), 'targetAttribute' => ['voting_id' => 'voting_id']],
        ];
    }

    /**
     * Связь с таблицей Голосование
     */
    public function getVoting() {
        return $this->hasOne(Voting::className(), ['voting_id' => 'voting_id']);
    }
    
    /*
     * Создаем запись регистрации участия в голосовании пользователя
     */
    public function registerIn($voting_id) {
        
        $user_id = Yii::$app->user->identity->id;
        // Проверяем наличие регистрации у пользователя на текущее голосовнаие
        $register = RegistrationInVoting::find()
                ->andWhere(['voting_id' => $voting_id, 'user_id' => $user_id])
                ->asArray()
                ->one();
        
//        if ($register !== null) {
//            return false;
//        }
        
        $number = mt_rand(10000, 99999);
        $this->voting_id = $voting_id;
        $this->user_id = Yii::$app->user->identity->id;
        $this->random_number = $number;
        $this->date_registration = time();
        return $this->save() ? true : false;
        
    }
    
    /*
     * Поиск записи о регистрации на голосование
     * 
     * @param integer $voting_id ID голосование
     * @param integer $user_id ID текущего пользователя
     */
    public static function findById($voting_id) {
        
        $user_id = Yii::$app->user->identity->id;
        $register = RegistrationInVoting::find()
                ->andWhere(['voting_id' => $voting_id, 'user_id' => $user_id])
                ->one();
        return $register;
    }
    
    /*
     * Удаление записи о регистрации на голосование
     * 
     * @param integer $voting_id ID голосование
     * @param integer $user_id ID текущего пользователя
     */
    public static function deleteRegisterById($voting_id) {
        
        $user_id = Yii::$app->user->identity->id;
        $register = RegistrationInVoting::find()
                ->andWhere(['voting_id' => $voting_id, 'user_id' => $user_id])
                ->one();
        
        if ($register !== null) {
            $register->delete();
        }
        
        return true;
        
    }
    
    /*
     * Генерауия нового СМС кода
     */
    public function generateNewSMSCode() {
        
        $new_value = $number = mt_rand(10000, 99999);
        $this->random_number = $new_value;
        return $this->save(false);
        
    }
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'voting_id' => 'Voting ID',
            'user_id' => 'User ID',
            'random_number' => 'Random Number',
            'date_registration' => 'Date Registration',
            'status' => 'Status',
        ];
    }

}
