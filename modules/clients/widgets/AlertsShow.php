<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/**
 * Виджет вывода сообщений для модуля Собственник, Арендатор
 */

class AlertsShow extends Widget {
    
    public function run() {
        
        return $this->render('alertsshow/view');
        
    }
}
