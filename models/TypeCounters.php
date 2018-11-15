<?php

    namespace app\models;
    use Yii;
    use \yii\db\ActiveRecord;
    use app\models\Counters;

/**
 * Виды приборов учета
 */
class TypeCounters extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName() {
        return 'type_counters';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['type_counters_name'], 'string', 'max' => 100],
        ];
    }
    
    public function getCounter() {
        return $this->hasMany(Counters::className(), ['counters_type_id' => 'type_counters_id']);
    }

    /**
     * Метки для полей
     */
    public function attributeLabels() {
        return [
            'type_counters_id' => 'Type Counters ID',
            'type_counters_name' => 'Type Counters Name',
        ];
    }
}
