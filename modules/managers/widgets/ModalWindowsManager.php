<?php

    namespace app\modules\managers\widgets;
    use yii\base\InvalidConfigException;
    use yii\base\Widget;

/*
 * Виджет вывода модальных окон
 */

class ModalWindowsManager extends Widget {
    
    // view модального окна
    public $modal_view;

    public function init() {
        
        parent::init();
        
        if ($this->modal_view == null) {
            throw new InvalidConfigException('Ошибка при передаче параметров. Не задан вид модального окна');
        }
        
    }

    public function run() {
        
        return $this->render('modalwindowsmanager/'. $this->modal_view, [
            'name_view' => $this->modal_view,]);
        
    }
    
}
