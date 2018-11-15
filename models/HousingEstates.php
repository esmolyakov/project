<?php

    namespace app\models;
    use Yii;
    use app\models\Houses;
    use yii\db\ActiveRecord;

/**
 * Жилой комплекс
 */
class HousingEstates extends ActiveRecord
{
    
    public $is_new = false;
    public $estate_name_drp;

    /**
     * Таблица в БД
     */
    public static function tableName()
    {
        return 'housing_estates';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            
            [['estate_name'], 'string', 'min' => 5, 'max' => 170],
            [['estate_town'], 'string', 'min' => 5, 'max' => 70],

            [['is_new', 'estate_name_drp'], 'safe'],
            [['estate_name_drp', 'is_new'], 'integer'],
            
            ['estate_name_drp', 'default', 'value' => 0],
            
            // Название ЖК, город - обязательны для заполнения, если установлен переключатель "Новый"
            [['estate_name', 'estate_town'], 
                'required', 
                'whenClient' => 'function(attribute, value) { return ($("#housingestates-is_new").is(":checked")) ? true : false; }'],
            
            ['estate_name_drp',
                'required',
                'whenClient' => 'function(attribute, value) { return ($("#housingestates-is_new").is(":checked")) ? false: true; }'],
            
        ];
    }

    /**
     * Связь с таблицей Дома
     */
    public function getHouse()
    {
        return $this->hasMany(Houses::className(), ['houses_estate_name_id' => 'estate_id']);
    }
    
    public static function getHousingEstateList() {
        
        $array = self::find()
                ->asArray()
                ->orderBy(['estate_name' => SORT_ASC])
                ->all();        
        
        return $array;
    }
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'estate_id' => 'Estate ID',
            'estate_name' => 'Нименование жилого комплекса',
            'estate_town' => 'Город',
            'estate_name_drp' => 'Жилой комплекс',
            'is_new' => 'Новый',
        ];
    }

}
