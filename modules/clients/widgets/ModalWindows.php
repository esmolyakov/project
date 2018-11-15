<?php

    namespace app\modules\clients\widgets;
    use yii\base\InvalidConfigException;
    use yii\base\Widget;

/*
 * Виджет вывода модальных окон
 */

class ModalWindows extends Widget {
    
    // view модального окна
    public $modal_view;
    
    // Список лицевых счетов
    public $list_account;


    public function init() {
        
        parent::init();
        
        if ($this->modal_view == null) {
            throw new InvalidConfigException('Ошибка при передаче параметров. Не задан вид модального окна');
        }
        
    }

    public function run() {
        
        return $this->render('modalwindows/'. $this->modal_view, [
            'name_view' => $this->modal_view, 
            'list_account' => $this->list_account]);
        
    }
    
}
