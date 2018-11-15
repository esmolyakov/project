<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use yii\helpers\ArrayHelper;
    use yii\base\UnknownPropertyException;
    use app\models\StatusRequest;

/**
 * Виджет формирования оценки для заявок
 */
class RatingRequest extends Widget {
    
    // Статус заявки
    public $_status = 0;
    // ID заявки
    public $_request_id;
    // Оценка
    public $_score;


    public function init() {
        
        // Если передан не верный статус или ID заявки, кидаем исключение
        if (!ArrayHelper::keyExists($this->_status, StatusRequest::getStatusNameArray()) || empty($this->_request_id)) {
            throw new UnknownPropertyException('Ошибка при передаче параметра');
        }
        
        parent::init();
        
    }
    
    public function run() {
        
        $score = $this->_score ? $this->_score : 0;
        
        // Вид виждета отрисовываем только для заявок со статусом закрыто
        if ($this->_status == StatusRequest::STATUS_CLOSE) {
            
            return $this->render('ratingrequest/rating_view', [
                'status' => $this->_status, 
                'request_id' => $this->_request_id, 
                'score' => $score]);
            
        }
        
    }
    
}
