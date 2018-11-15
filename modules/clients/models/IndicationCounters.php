<?php

    namespace app\modules\clients\models;
    use yii\base\Model;

/**
 * Текущие показания приборов
 */
class IndicationCounters extends Model {
    
    public $couinter_id;
    public $date_reading;
    public $indication;
    
//    public function rules() {
//        return [
//            [['couinter_id', 'date_reading', 'indication'], 'required'],
//            ['indication', 'string', 'min' => 1, 'max' => 12],
//        ];
//    }
    
    
    
}
