<?php

    namespace app\modules\clients\models\form;
    use yii\base\Model;

class CheckSMSVotingForm extends Model {
    
    public $number1;
    public $number2;
    public $number3;
    public $number4;
    public $number5;
    
    public function rules() {
        return [
            [['number1', 'number2', 'number3', 'number4', 'number5'], 'required'],
            [['number1', 'number2', 'number3', 'number4', 'number5'], 'string', 'min' => 1, 'max' => 1],
        ];
    }
    
}
