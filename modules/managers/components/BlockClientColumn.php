<?php

    namespace app\modules\managers\components;
    use yii\grid\DataColumn;
    use yii\helpers\Html;
    use app\models\User;

/**
 * Формирование колонки "Блокировать/Разблокировать" в таблице Клиенты
 */
class BlockClientColumn extends DataColumn {
    
    // Название поля  БД
    public $attribute = 'status';
    // Заголовок колонки
    public $header = 'Статус';
    // Действие контроллера
    public $action = 'block-client';
    // Пост параметр
    public $client_key = 'clientId';
    // Пост параметр
    public $status_key = 'statusClient';
    // Метод передачи ajax
    public $ajax_method = 'POST';

    /*
     * Формируем ajax запрос на каждый элемент чекбокса в колонке таблицы
     */
    public function init() {
        
        $this->grid->view->registerJs("
            $('body').on('change', 'input[type=checkbox]', function(e) {
                e.preventDefault();
                // Получаем data атрибут ID собственика
                var clientId = $(this).data('clientId');
                // Получаем data атрибут статус собственика
                var statusClient = $(this).data('status');
                // Собираем все элементы, которые содержат одинаковые data атрибут ID собственика
                var btnValue = $('[data-client-id=' + clientId + ']');
                
                $.ajax({
                    url: '" . $this->action . "',
                    type: '" . $this->ajax_method . "',
                    data: {
                        {$this->client_key} : clientId,
                        {$this->status_key} : statusClient,
                    },
                    success: function(response) {
                        if (response.status == " . User::STATUS_BLOCK . ") {
                            btnValue.prop('checked', true);
                            btnValue.data('status', 1);
                        } else {
                            if (response.status == " . User::STATUS_ENABLED . ") {
                                btnValue.prop('checked', false);
                                btnValue.data('status', 2);
                            }
                        }
                    },
                    error: function() {
                        console.log('#2000 - 01: Ошибка Ajax запроса');
                    },
                });
                
                return false;
                
            });
        ");
        
    }

    /*
     *  Формируем колонку checkbox Блокировать/Разблокировать учетную запись Собственника 
     * 
     * @param integer $data['status'] == User::STATUS_ENABLED (1) Собственник активен
     * @param integer $data['status'] == User::STATUS_BLOCK (2) Собственник заблокирован
     */    
    protected function renderDataCellContent($data) {
        
        if ($data['status'] == User::STATUS_ENABLED) {
            return 
                Html::checkbox('', false,
                        [
                            'class' => 'form-control status_btn',
                            'data' => [
                                'client-id' => $data['client_id'],
                                'status' => User::STATUS_BLOCK,
                            ],
                        ]);
        } elseif ($data['status'] == User::STATUS_BLOCK) {
            return 
                Html::checkbox('', true,
                        [
                            'class' => 'form-control status_btn',
                            'data' => [
                                'client-id' => $data['client_id'],
                                'status' => User::STATUS_ENABLED,
                            ],
                        ]);          
        }
        
    }
    
    
}
