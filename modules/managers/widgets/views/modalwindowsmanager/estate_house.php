<?php

?>
<?php /* Ошибка добавления характеристики, загрузки документа */ ?>
<div class="modal fade estate_house" id="estate_house_message_manager" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="modal__text"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<?php /* Подтверждение удаления статуса Должник и примечания у квартиры */ ?>
<div class="modal fade estate_note_message" id="estate_note_message_manager" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title">Снять статус "Должник"</h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Вы действительно хотите снять статус "Должник" с выбранной квартиры?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary estate_note_message__yes">Да</button>
                <button class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
