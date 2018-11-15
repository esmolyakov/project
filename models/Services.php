<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Units;
    use app\models\CategoryServices;
    use yii\helpers\ArrayHelper;

/**
 * Услуги
 */
class Services extends ActiveRecord
{
    
    const TYPE_SERVICE = 0;
    const TYPE_PAY = 1;
    
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            
            [[
                'services_category_id',
                'services_name',
                'services_unit_id',
                'services_cost', 
                'isType'], 'required'],
            
            [['services_category_id', 'isType'], 'integer'],
            
            ['services_cost', 'number', 'numberPattern' => '/^\d+(.\d{1,2})?$/'],
            
            [['services_name', 'services_image'], 'string', 'min' => '10', 'max' => 255],
            
            ['services_description', 'string', 'min' => 10, 'max' => 1000],
        ];
    }
    
    /*
     * Связь с таблицей Категории услуг/Вид услуг
     */
    public function getCategory() {
        return $this->hasOne(CategoryServices::className(), ['category_id' => 'services_category_id']);
    }
    
    /*
     * Связь с таблицей Единицы измерения
     */
    public function getUnit() {
        return $this->hasOne(Units::className(), ['units_id' => 'services_unit_id']);
    }
    
    /*
     * Формирование массива услуг
     */
    public static function getServicesNameArray() {
        
        $array = static::find()
                ->asArray()
                ->all();
        
        return ArrayHelper::map($array, 'services_id', 'services_name');
    }

    /*
     * Получаем только платные услуги
     */
    public static function getPayServices() {
        
        $pay_services = self::find()
                ->joinWith('category')
                ->asArray()
                ->orderBy(['category_name' => SORT_ASC, 'services_name' => SORT_ASC])
                ->all();
        
        return $pay_services;
    }
    
    /*
     * Получить ID услуги
     */
    public function getId() {
        return $this->services_id;
    }
    
    /*
     * Список типов услуг
     */
    public static function getTypeNameArray () {
        return [
            self::TYPE_SERVICE => 'Услуга',
            self::TYPE_PAY => 'Платная услуга',
        ];
    }
    
    /*
     * Поиск услуги по ID
     */
    public static function findByID($service_id) {
        return self::find()
                ->where(['services_id' => $service_id])
                ->one();
    }
    
    /*
     * Метод смены типы усуги
     * Усулуга/Платная услуга
     */
    public function checkType($type) {
        
        if ($type == self::TYPE_PAY) {
            $this->isType = self::TYPE_PAY;
        } elseif ($type == self::TYPE_SERVICE) {
            $this->isType = self::TYPE_SERVICE;
        }
        
        return $this->save() ? true : false;
    }

    /*
     * Загрузка изображения услуги
     */    
    public function uploadImage($file) {
        
        $current_image = $this->services_image;
        
        if ($this->validate()) {
            if ($file) {
                $this->services_image = $file;
                $dir = Yii::getAlias('images/services/');
                $file_name = 'service_' . time() . '.' . $this->services_image->extension;
                $this->services_image->saveAs($dir . $file_name);
                $this->services_image = '/' . $dir . $file_name;
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                $this->services_image = $current_image;
            }
            return $this->save() ? true : false;
        }
        
        return false;
    }
    
    /*
     * Получить изображение услуги
     * В случае, если изображени для услуги не было задано - выводится изображение по умолчанию
     */
    public function getImage() {
        if (empty($this->services_image)) {
            return Yii::getAlias('@web') . '/images/not_found.png';
        }
        return Yii::getAlias('@web') . $this->services_image;
    }

    
    /**
     * Массив статусов заявок
     */
    public function attributeLabels()
    {
        return [
            'services_id' => 'Services ID',
            'services_name' => 'Наименование услуги',
            'services_category_id' => 'Вид услуги',
            'services_cost' => 'Стоимость',
            'services_image' => 'Изображение',
            'services_description' => 'Описание',
            'isType' => 'Тип услуги',
            'services_unit_id' => 'Единица измерения',
        ];
    }
}
