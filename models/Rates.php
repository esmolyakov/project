<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Units;
    use app\models\CategoryServices;

/**
 * Тарифы
 */
class Rates extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'rates';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            
            [['rates_name', 'rates_category_id', 'rates_unit_id', 'rates_cost'], 'required'],
            [['rates_name'], 'string', 'min' => 3, 'max' => 255],
//            ['rates_cost', 'number', 'numberPattern' => '/^\d+(.\d{1,2})?$/'],
            [['rates_category_id', 'rates_unit_id'], 'integer'],
        ];
    }
    
    /*
     * Связь с таблицей Единицы измерения
     */
    public function getUnit() {
        return $this->hasOne(Units::className(), ['units_id' => 'rates_unit_id']);
    }

    /*
     * Свзяь с таблицей Категории услуг
     */
    public function getCategory() {
        return $this->hasOne(CategoryServices::className(), ['category_id' => 'rates_category_id']);
    }
    
    /*
     * Поиск тарифа по ID услуги
     */
    public static function findByServiceID($service_id) {
        return self::find()
                ->where(['rates_service_id' => $service_id])
                ->one();
    }
    
    /**
     * Массив статусов заявок
     */
    public function attributeLabels()
    {
        return [
            'rates_id' => 'Rates ID',
            'rates_name' => 'Наименование тарифы',
            'rates_category_id' => 'Вид услуги',
            'rates_unit_id' => 'Единица измерения',
            'rates_cost' => 'Стоимость',
        ];
    }
}
