<?php

    namespace app\widgets;
    use yii\base\Widget;

/**
 * Слайдер на главной странце портала
 */
class Slider extends Widget {
    
    public function init() {
        
        parent::init();
        
    }
    
    public function run() {
        
        return $this->render('slider/default');
    }
    
}
