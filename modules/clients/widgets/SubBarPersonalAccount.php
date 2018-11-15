<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/**
 * Виджет дополнительного навигационного меню для раздела Лицевой счет
 */
class SubBarPersonalAccount extends Widget {
    
    public function init() {
        
        parent::init();
    }
    
    public function run() {
        
        return $this->render('subbarpersonalaccount/default');
    }
    
}
