<?php

    namespace app\helpers;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use app\models\StatusRequest;    

/**
 * Формирование вывода статусов
 * Заявок
 */
class StatusHelpers {
    
    public static function requestStatus($status, $value = null) {
        
        $btn_css = '';
        $voting_bnt = '';
        
        // Стили для кнопок статусов
        $css_classes = [
            'badge badge-pill req-badge req-badge-new', 
            'badge badge-pill req-badge req-badge-work', 
            'badge badge-pill req-badge req-badge-complete', 
            'badge badge-pill req-badge req-badge-rectification', 
            'badge badge-pill req-badge req-badge-close',
        ];
    
        
        if ($status == null) {
            return 'Не задан';
        }
        
        // Получаем текстовое обозначение статуса
        $status_name = ArrayHelper::getValue(StatusRequest::getStatusNameArray(), $status);        
        
        if ($status == StatusRequest::STATUS_NEW) {
            $btn_css = '<span class="' . $css_classes[0] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_IN_WORK) {
            $btn_css = '<span class="' . $css_classes[1] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_PERFORM) {
            $btn_css = '<span class="' . $css_classes[2] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_FEEDBAK) {
            $btn_css = '<span class="' . $css_classes[3] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_CLOSE) {
            $btn_css = '<span class="' . $css_classes[4] . '">' . $status_name . '</span>';
        }
        
        if ($status == StatusRequest::STATUS_CLOSE) {
            $voting_bnt = Html::button('Оценить', ['class' => 'blue-outline-btn req-table-btn', 'data-request' => $value]);
        }
        
        return $btn_css . '<br />' . $voting_bnt;
        
    }
    
}
