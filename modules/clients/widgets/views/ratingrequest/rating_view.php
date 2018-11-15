<?php

    use yii\helpers\Html;

/* 
 * Оценка заявок
 */
?>

<div id="star" data-request="<?= $request_id ?>" data-score-reguest="<?= $score ?>"></div>

<div id="score-modal-message" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Оценка заявки</h4>
            </div>
            <div class="modal-body">
                <p>Спасибо! Ваша оценка принята</p>
                <p>
                    Так же предлагаем вам ответить на ряд вопросов:
                    // TODO
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Ответить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>