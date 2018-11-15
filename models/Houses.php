<?php

    namespace app\models;
    use yii\db\ActiveRecord;
    use Yii;
    use rico\yii2images\behaviors\ImageBehave;
    use app\models\Flats;
    use app\models\HousingEstates;
    use app\models\CharacteristicsHouse;

/**
 * Дома
 */
class Houses extends ActiveRecord
{
    const SCENARIO_EDIT_DESCRIPRION = 'edit description house';
    const SCENARIO_LOAD_FILE = 'load new file';
    
    public $upload_file;
    public $upload_files;


    /**
     * Таблица в БД
     */
    public static function tableName()
    {
        return 'houses';
    }
    
    public function behaviors () {
        return [
            'image' => [
                'class' => ImageBehave::className(),
            ],
        ];
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [[
                'houses_estate_name_id', 
                'houses_street', 'houses_number_house', 
                'houses_description'], 'required'],
            
            [['houses_estate_name_id'], 'integer'],
            
            [['houses_street'], 'string', 'max' => 100],
            
            [['houses_description'], 'string', 'max' => 255],
            
            [['houses_number_house'], 'string', 'max' => 10],
            
//            [['houses_number_house'], 'validateNumberHouse'],
            
            [['houses_estate_name_id'], 'exist', 'skipOnError' => true, 'targetClass' => HousingEstates::className(), 'targetAttribute' => ['houses_estate_name_id' => 'estate_id']],

            ['houses_description', 'required', 'on' => self::SCENARIO_EDIT_DESCRIPRION],
            ['houses_description', 'string', 'min' => 10, 'max' => 255, 'on' => self::SCENARIO_EDIT_DESCRIPRION],
            
            ['upload_file', 'required', 'on' => self::SCENARIO_LOAD_FILE],
            [['upload_file'],
                'file', 
                'maxSize' => 256 * 1000,
                'extensions' => 'doc, docx, pdf, xls, xlsx, png, jpg, jpeg',
                'on' => self::SCENARIO_LOAD_FILE],
            
            [['upload_files'],
                'file', 
                'maxSize' => 256 * 1000,
                'extensions' => 'doc, docx, pdf, xls, xlsx, png, jpg, jpeg',
                'maxFiles' => 4,
            ],            
            
        ];
    }
    
    public function validateNumberHouse() {
        
        $house = Houses::find()
                ->where(['houses_estate_name_id' => $this->houses_estate_name_id])
                ->andWhere(['houses_street' => $this->houses_street])
                ->andWhere(['houses_number_house' => $this->houses_number_house])
                ->asArray()
                ->one();
        
        if ($house !== null) {
            $errorMsg = 'Указанный номер дома в выбранном жилом комплексе не является уникальным';
            $this->addError('houses_number_house', $errorMsg);
        }
        
    }

    /**
     * Связь с таблицей Квартиры
     */
    public function getFlat() {
        return $this->hasMany(Flats::className(), ['flats_house_id' => 'houses_id']);
    }

    /**
     * Связь с таблицей Жилой комплекс
     */
    public function getEstate() {
        return $this->hasOne(HousingEstates::className(), ['estate_id' => 'houses_estate_name_id']);
    }

    /**
     * Связь с таблицей Характеристики дома
     */
    public function getCharacteristic() {
        return $this->hasMany(CharacteristicsHouse::className(), ['characteristics_house_id' => 'houses_id']);
    }
    
    public static function findHouseById($house_id) {
        return self::find()
                ->where(['houses_id' => $house_id])
                ->one();
    }
    
    public static function getAllHouses() {
        
        return self::find()
                ->select(['estate_id', 'estate_name', 'estate_town', 'houses_id', 'houses_street', 'houses_number_house', 'houses_description'])
                ->joinWith(['estate'])
                ->orderBy([
                    'estate_name' => SORT_ASC,
                    'estate_town' => SORT_ASC,
                    'houses_street' => SORT_ASC,
                    'houses_number_house' => SORT_ASC])
                ->asArray()
                ->all();
        
    }    
    
    /*
     * Загрузка прикрепленного документа
     */
    public function uploadFile($file) {
        
        if ($file) {
            $file_name = $file->basename;
            $path = 'upload/store/' . $file_name . '.' . $file->extension;
            // Получаем имя файла
            $file->saveAs($path);
            $this->attachImage($path, false, $file_name);
            @unlink($path);
            return true;
        } else {
            return false;
        }
    }

    /*
     * Загрузка прикрепленных документов
     */
    public function uploadFiles($files) {
        
        if ($files) {
            foreach ($files as $file) {
                $file_name = $file->basename;
                $path = 'upload/store' . $file_name . '.' . $file->extension;
                // Получаем имя файла
                $file->saveAs($path);
                $this->attachImage($path, false, $file_name);                
                @unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }

    /*
     * Список всех домов жилого массива
     */
    public static function getHousesList() {
        return self::find()
                ->joinWith(['estate'])
                ->select(['houses_id', 'estate_name', 'estate_town', 'houses_street', 'houses_number_house'])
                ->asArray()
                ->orderBy(['estate_town' => SORT_ASC, 'houses_street' => SORT_ASC, 'houses_number_house' => SORT_ASC])
                ->all();
    }    
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'houses_id' => 'Houses ID',
            'houses_estate_name_id' => 'Жилой комплекс',
            'houses_street' => 'Улица',
            'houses_number_house' => 'Номер дома',
            'houses_description' => 'Описание',
            'upload_file' => 'Загружаемый файл',
            'upload_files' => 'Файлы',
        ];
    }


}
