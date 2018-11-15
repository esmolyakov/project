<?php

/* 
 * Модальное окно
 * Удалить диспетчера
 */ 
?>
<div class="modal fade" id="delete_service" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title">
                    Удалить услугу
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Внимание, удаление выбранной услуги приведет также к удалению тарифа, закрепленного за данной услугой.
                    <br />
                    Вы собираетесь удалить следующее услугу:
                    <span id="srv_name"></span>
                    <br />
                    Продолжить?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger delete_srv__del" id ="confirm_delete-srv" data-dismiss="modal">Удалить</button>
                <button class="btn btn-primary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>