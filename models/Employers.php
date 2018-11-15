<?php

    namespace app\models;
    use Yii;
    use app\models\Requests;

class Employers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employers_name', 'employers_surname', 'employers_second_name', 'employers_department_id', 'employers_posts_id', 'employers_birthday'], 'required'],
            [['employers_department_id', 'employers_posts_id'], 'integer'],
            [['employers_birthday'], 'safe'],
            [['employers_name', 'employers_surname', 'employers_second_name'], 'string', 'max' => 70],
        ];
    }
    
    
    public static function findByID($employer_id) {
        return self::find()
                ->andWhere(['employers_id' => $employer_id])
                ->one();
    }
    
    public function getId() {
        return $this->employers_id;
    }
    
    /*
     * Получить полное имя
     * Фамилия Имя Отчества Сотрудника
     */
    public function getFullName() {
        return $this->employers_surname . ' ' 
                . $this->employers_name . ' '
                . $this->employers_second_name;
    }
    
    /*
     * Получить все зявки Специалиста
     */
    public function getRequests() {
        return Requests::find()
                ->andWhere(['requests_specialist_id' => $this->employers_id])
                ->andWhere(['!=', 'status', StatusRequest::STATUS_CLOSE])
                ->asArray()
                ->all();
    }

    /*
     * Получить все заявки на платные услуги Специалиста
     */
    public function getPaidServices() {
        return PaidServices::find()
                ->andWhere(['services_specialist_id' => $this->employers_id])
                ->andWhere(['!=', 'status', StatusRequest::STATUS_CLOSE])
                ->asArray()
                ->all();
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employers_id' => 'Employers ID',
            'employers_name' => 'Имя',
            'employers_surname' => 'Фамилия',
            'employers_second_name' => 'Отчество',
            'employers_department_id' => 'Подразделение',
            'employers_posts_id' => 'Должность (Специальность)',
            'employers_birthday' => 'Дата рождения',
        ];
    }
}
