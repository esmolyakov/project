<?php

    namespace app\modules\managers\components;
    use yii\grid\DataColumn;
    use yii\helpers\Html;
    use app\models\Services;

/**
 * Формирование колонки для установки статуса Платная услуга
 */
class PayServiceColumn extends DataColumn {
    
    // Название поля БД
    public $attribute = 'type';
    // Залоговок колонки
    public $header = 'Платная услуга';
    // Действие контроллера
    public $action = 'check-type-service';
    // Пост параметр ID услуги
    public $service_key = 'serviceId';
    // Пост параметр тип услуги
    public $type_key = 'typeService';
    // Метод передачи ajax запроса
    public $ajax_method = 'POST';
    
    /*
     * Формируем ajax запрос на каждый элемент чекбокса в колонке ьаблицы
     */
    public function init() {
        $this->grid->view->registerJs("
            $('body').on('change', 'input[type=checkbox]', function(e){
                e.preventDefault();
                var checkData = $(this);
                // Получаем дата атрибут ID услуги
                var serviceId = $(this).data('serviceId');
                // Получаем дата атрибут тип заявки
                var typeService = $(this).data('type');
                
                $.ajax({
                    url: '" . $this->action . "',
                    type: '" . $this->ajax_method . "',
                    data: {
                        {$this->service_key}: serviceId,
                        {$this->type_key}: typeService,
                    },
                    success: function(response) {
                        if (response.type == " . Services::TYPE_PAY . ") {
                            checkData.data('type', ". Services::TYPE_SERVICE .");
                        } else {
                            if (response.type == " . Services::TYPE_SERVICE . ") {
                                checkData.data('type', " . Services::TYPE_PAY . ");
                            }
                        }
                        console.log(response.type);
                    },
                    error: function() {
                        console.log('#2000 - 05: Ошибка Ajax запроса');
                    }
                });
                return false;
            });
        ");
    }
    
    /*
     *  Формируем колонку checkbox Блокировать/Разблокировать учетную запись Собственника 
     * 
     * @param integer $data['tupe'] == Services::TYPE_PAY (1) Платная услуга
     * @param integer $data['status'] == Services::TYPE_SERVICE (0) Услуга
     */
    protected function renderDataCellContent($data) {
        
        if ($data['type'] == Services::TYPE_PAY) {
            return 
                Html::checkbox('', true, [
                    'class' => 'type_srv',
                    'data' => [
                        'service-id' => $data['id'],
                        'type' => Services::TYPE_SERVICE,
                    ],
                ]);
        } elseif ($data['type'] == Services::TYPE_SERVICE) {
            return
                Html::checkbox('', false, [
                    'class' => 'type_srv',
                    'data' => [
                        'service-id' => $data['id'],
                        'type' => Services::TYPE_PAY,
                    ],
                ]);
        }
        
    }
    
}
