<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Houses;

/**
 *  Характеристики дома
 */
class CharacteristicsHouse extends ActiveRecord
{
    const SCENARIO_ADD_CHARACTERISTIC = 'add new characteristic';
    
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'characteristics_house';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['characteristics_name', 'characteristics_value'], 'required'],
            [['characteristics_house_id'], 'integer'],
            
            [['characteristics_name'], 'string', 'max' => 255],
            [['characteristics_value'], 'string', 'max' => 170],
            
            [['characteristics_name', 'characteristics_value'], 'required', 'on' => self::SCENARIO_ADD_CHARACTERISTIC],
            [['characteristics_name', 'characteristics_value'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_ADD_CHARACTERISTIC],
            ['characteristics_name', 'string', 'min' => 5, 'max' => 255, 'on' => self::SCENARIO_ADD_CHARACTERISTIC],
            ['characteristics_value', 'string', 'min' => 1, 'max' => 170, 'on' => self::SCENARIO_ADD_CHARACTERISTIC],
            
            [['characteristics_house_id'], 'exist', 'skipOnError' => true, 'targetClass' => Houses::className(), 'targetAttribute' => ['characteristics_house_id' => 'houses_id']],
        ];
    }

    /**
     * Связь с таблицей Дома
     */
    public function getHouse() {
        return $this->hasOne(Houses::className(), ['houses_id' => 'characteristics_house_id']);
    }
    
    /*
     * Получить все характеристики по заданному дому
     */
    public static function getCharacteristicsByHouse($house_id) {
        return self::find()
                ->where(['characteristics_house_id' => $house_id])
                ->asArray()
                ->all();
    }
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'characteristics_id' => 'Characteristics ID',
            'characteristics_house_id' => 'Characteristics House ID',
            'characteristics_name' => 'Наименование характеристики',
            'characteristics_value' => 'Значение характеристики',
        ];
    }

}
