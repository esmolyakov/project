<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;

/**
 * Категории услуг
 */
class CategoryServices extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'category_services';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['category_name'], 'string', 'max' => 255],
        ];
    }
    
    /*
     * Связь с таблице Услуги
     */
    public function getService() {
        return $this->hasMany(Services::className(), ['services_category_id' => 'category_id']);
    }
    
    /*
     * Формирование категорий заявок
     */
    public static function getCategoryNameArray() {
        
        $array = static::find()
                ->asArray()
                ->all();
        
        return ArrayHelper::map($array, 'category_id', 'category_name');
    }    
    
    /*
     * Получить все платные услуги
     */
    public static function getAllCategory() {
        return self::find()
                ->joinWith(['service'])
                ->andWhere(['services.isType' => 1])
                ->all();
    }    
    
    /**
     * Массив статусов заявок
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'category_name' => 'Category Name',
        ];
    }
}
