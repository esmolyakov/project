<?php

    namespace app\modules\managers\widgets;
    use yii\base\Widget;

/**
 * Виджет вывода сообщений для модуля Администратор
 */
class AlertsShow extends Widget {
    
    public function run() {
        
        return $this->render('alertsshow/view');
        
    }
}
