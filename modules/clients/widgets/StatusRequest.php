<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\StatusRequest as ModelStatusRequest;

/**
 * Виджет для формирования статусов заявок
 */
class StatusRequest extends Widget {
    
    public $status_requests = [];
    public $css_classes = [
        'btn req-btn req-btn-new', 
        'btn req-btn req-btn-work', 
        'btn req-btn req-btn-complete', 
        'btn req-btn req-btn-rectification', 
        'btn req-btn req-btn-close'
    ];
    
    public function init() {
        
        $this->status_requests = ModelStatusRequest::getUserStatusRequests();
        parent::init();
    }
    
    public function run() {
        
        return $this->render('statusrequest/default', [
            'status_requests' => $this->status_requests,
            'css_classes' => $this->css_classes,
        ]);
    }
    
}
