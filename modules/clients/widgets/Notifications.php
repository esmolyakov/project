<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/**
 * Виджет уведомлений
 */
class Notifications extends Widget {
    
    public function init() {
        
        parent::init();
    }
    
    public function run() {
        
        return $this->render('notifications/default');
    }
    
}
