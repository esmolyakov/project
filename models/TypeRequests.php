<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;

/**
 * Вид заявок
 */
class TypeRequests extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'type_requests';
    }
    
    /*
     * Формирование видов (типов) заявок
     */
    public static function getTypeNameArray() {
        $array = static::find()->asArray()->all();
        return ArrayHelper::map($array, 'type_requests_id', 'type_requests_name');
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['type_requests_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'type_requests_id' => 'Type Requests ID',
            'type_requests_name' => 'Type Requests Name',
        ];
    }
}
