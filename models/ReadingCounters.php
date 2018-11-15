<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Counters;

/**
 * Показания приборов учетов
 */
class ReadingCounters extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName() {
        return 'reading_counters';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['reading_counter_id', 'date_reading', 'user_id'], 'integer'],
            [['readings_indication'], 'string', 'max' => 50],
        ];
    }
    
    /*
     * 
     */
    public function getCounter() {
        return $this->hasOne(Counters::className(), ['counters_id' => 'reading_counter_id']);
    }
    
    /**
     * Метки для полей
     */
    public function attributeLabels() {
        return [
            'reading_id' => 'Reading ID',
            'reading_counter_id' => 'Reading Counter ID',
            'readings_indication' => 'Readings Info',
            'date_reading' => 'Date Reading',
            'user_id' => 'User ID',
        ];
    }
}
