<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\TypeCounters;
    use app\models\ReadingCounters;

/**
 * Приборы учета
 */
class Counters extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;

    /**
     * Таблица из БД
     */
    public static function tableName() {
        return 'counters';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['counters_type_id', 'counters_account_id', 'counters_house_id', 'date_check', 'isActive'], 'integer'],
            [['counters_description'], 'string', 'max' => 1000],
            [['counters_number'], 'string', 'max' => 70],
        ];
    }
    
    /*
     * Связь с таблицей "Типы приборов учета"
     */
    public function getTypeCounter() {
        return $this->hasOne(TypeCounters::className(), ['type_counters_id' => 'counters_type_id']);
    }
    
    /*
     * Связь с таблицей "Показания приборов учета"
     */
    public function getReadingCounter() {
        return $this->hasMany(ReadingCounters::className(), ['reading_counter_id' => 'counters_id']);
    }
    
    public static function findCountersClient($account_id) {
        
        return self::find()
                ->andWhere(['counters_account_id' => $account_id])
                ->with(['typeCounter', 'readingCounter']);
        
    }
    
    /*
     * Получить показания приборов учета по номеру текущего месяца
     * @param $last_indication Показания предыдущего месяца от текущего
     * @param $current_indication Показания текущего месяца
     * 
     * --------------------------------> ВРЕМЕННЫЙ КОСТЫЛЬ
     * 
     */
    public static function getReadingCurrent($account_id, $month, $year) {
        
        /*
         * Период запрошенных даных по показаниям прибород (текущий месяц, предыдущий месяц)
         * Если из dropDownList выбран текущий месяц Январь == 1, то 
         *      - данные должны быть за Декабрь предыдущего года - Ноябрь предыдущего года
         */
        if ($month == 1) {
            $current_month = $month + 11;
            $current_year = $year - 1;
            $last_month = $month + 10;
            $last_year = $year - 1;
        } else 
            /*
             * Если из dropDownList выбран текущий месяц Февраль == 2, то 
             *      - данные должны быть за Январь текущего года - Декабрь предыдущего года
             */
            if ($month == 2) {
            $current_month = $month - 1;
            $current_year = $year;
            $last_month = $month + 10;
            $last_year = $year - 1;
        } else {
            /*
             * В остальных случаях текущий месяц на 1 меньше выбранного,
             *      а предыдущий на 2 меньше выбранного
             *      год без изменений
             */
            $current_month = $month - 1;
            $current_year = $year;
            $last_month = $month - 2;
            $last_year = $year;
        }
        
        $current_indication = (new \yii\db\Query())
                ->select("reading_counter_id, date_reading, readings_indication")
                ->from('reading_counters')
                ->where([
                    'MONTH(FROM_UNIXTIME(date_reading))' => $current_month,
                    'YEAR(FROM_UNIXTIME(date_reading))' => $current_year,
                ]);
        
        // Проверяем есть ли показания за текущий месяц
        if ($current_indication->all()) {
            /* 
             * Если показания за текущий месяц есть, то формируем запрос на получение даннах 
             * за текущий и предыдущий месяца
             */

            $query_indication = (new \yii\db\Query())
                ->select('t1.counters_number, t1.counters_id, t1.date_check, t4.type_counters_name,'
                        . 't2.date_reading as last_date, t2.readings_indication as last_ind,'
                        . 't3.date_reading as current_date, t3.readings_indication as current_ind')
                ->from('counters AS t1')
                ->join('LEFT JOIN', 'reading_counters as t2', 't1.counters_id = t2.reading_counter_id')
                ->join('LEFT JOIN', 'reading_counters as t3', 't1.counters_id = t3.reading_counter_id')
                ->join('LEFT JOIN', 'type_counters AS t4', 't1.counters_id = t4.type_counters_id')
                ->where(['t1.counters_account_id' => $account_id])
                ->andWhere([
                    'MONTH(FROM_UNIXTIME(t2.date_reading))' => $last_month,
                    'YEAR(FROM_UNIXTIME(t2.date_reading))' => $last_year,
                    'MONTH(FROM_UNIXTIME(t3.date_reading))' => $current_month,
                    'YEAR(FROM_UNIXTIME(t3.date_reading))' => $current_year])
                ->groupBy('counters_id');

        } else {
            /* 
             * Если показания за текущий месяц не внесены, то формируем запрос на получение даннах 
             * только за предыдущий месяц
             */

            $query_indication = (new \yii\db\Query())
                ->select('t1.counters_number, t1.counters_id, t1.date_check, t4.type_counters_name,'
                        . 't2.date_reading as last_date, t2.readings_indication as last_ind,')
                ->from('counters AS t1')
                ->join('LEFT JOIN', 'reading_counters as t2', 't1.counters_id = t2.reading_counter_id')
                ->join('LEFT JOIN', 'type_counters AS t4', 't1.counters_id = t4.type_counters_id')
                ->where(['t1.counters_account_id' => $account_id])
                ->andWhere([
                    'MONTH(FROM_UNIXTIME(t2.date_reading))' => $last_month,
                    'YEAR(FROM_UNIXTIME(t2.date_reading))' => $last_year,
                ])
                ->groupBy('counters_id');

        }
        
        return $query_indication;

    }
    
    /**
     * Метки для полей
     */
    public function attributeLabels() {
        return [
            'counters_id' => 'Counters ID',
            'counters_type_id' => 'Counters Type ID',
            'counters_number' => 'Counters Number',
            'counters_description' => 'Counters Description',
            'counters_account_id' => 'Counters Account ID',
            'counters_house_id' => 'Counters House ID',
            'date_check' => 'Date Check',
            'isActive' => 'Is Active',
        ];
    }
}
