<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\PersonalAccount;
    use app\models\User;

/**
 * Промежуточная таблица для связи Пользователй и Лицевого счета
 *      Один пользователь может иметь несколько лицевых счетов
 *      Один лицевой счет может иметь несколько пользователей (собственника и 1(+) арендатора)
 */
class AccountToUsers extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'account_to_users';
    }

    public function rules()
    {
        return [
            [['account_id', 'user_id'], 'integer'],
        ];
    }
    
    /*
     * Связь с таблицей Пользователи
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }
    
    /*
     * Свзяь с таблицей Лицевой счет
     */
    public function getPersonalAccount() {
        return $this->hasOne(PersonalAccount::className(), ['account_id' => 'account_id']);
    }
    
    public static function findByUserID($user_id) {
        return self::find()
                ->andWhere(['user_id' => $user_id])
                ->one();
    }    
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'user_id' => 'User ID',
        ];
    }
}
