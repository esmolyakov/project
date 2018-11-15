<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\Modal;
    use app\helpers\FormatHelpers;
    use app\models\RegistrationInVoting;
    use app\modules\clients\widgets\ModalWindows;
    use app\models\Voting;

/* 
 * Просмотр отдельного голосования
 */
$_start = strtotime($voting['voting_date_start']);
$_end = strtotime($voting['voting_date_end']);
$_now = time();
$btn_disabled = ($_start > time() || $_end < time()) ? true : false;

$this->title = $voting['voting_title'];
?>

<div class="container-voting">
    <div class="preview">
        <div class="rt-btn">
            <!--<button class="btn bt-hop" data-toggle="modal" data-target="#frostedBk">ПРИНЯТЬ УЧАСТИЕ</button>-->
            <?= Html::button('ПРИНЯТЬ УЧАСТИЕ', [
                    'class' => 'btn bt-hop',
                    'id' => 'get-voting-in',
                    'data-voting' => $voting['voting_id'],
                    'disabled' => $btn_disabled,
            ]) ?>            
        </div>
        <div class="rectengle-left">
            <?= Html::img('/images/clients/' . ($voting['status'] == Voting::STATUS_CLOSED ? 'check.svg' : 'flag.svg'), ['class' => 'icons-clock']) ?>
            <?= FormatHelpers::statusNameVoting($voting['status']) ?>
        </div>
        <div class="rectengle-center">
            <?= FormatHelpers::numberOfDaysToFinishVote($voting['voting_date_start'], $voting['voting_date_end']) ?>
        </div>
        <div class="saturday-work">
            <h2 class="saturday-worky">
                <?= $voting['voting_title'] ?>
            </h2>
            <p class="text-saturday">
                <?= $voting['voting_text'] ?>
            </p>
        </div>
    </div>
    
    <div class="voting-tool">
        <div class="voted voted-root">
            <button type="button" class="battom-payment bt-voting1">#TODO</button>
            Проголосовало
        </div>
	<div class="photo-voted photo-voted-root">
            photo list
<!--            <img class="photo1 voted-img" src="/assets/img/noname.jpg" alt="">
            <img class="photo1 voted-img" src="/assets/img/noname.jpg" alt="">
            <img class="photo1 voted-img" src="/assets/img/noname.jpg" alt="">
            <img class="photo1 voted-img" src="/assets/img/noname.jpg" alt="">
            <img class="photo1 voted-img" src="/assets/img/noname.jpg" alt="">
            <img class="photo1 voted-img" src="/assets/img/noname.jpg" alt="">-->
        </div>
    </div>
    
    <div class="results">
        <?php foreach ($voting['question'] as $key => $question) : ?>
            <div class="start start1">
                <?= $question['questions_text'] ?>
                <!--<button type="button" class="battom-payment bt-voting1 btr-start1">12</button>Проголосовало-->
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php /*
<div class="clients-default-index">
    
    <h1><?= $this->title ?></h1>
    
    <div class="col-md-3">
        <?= Html::img('@web' . $voting['voting_image'], ['alt' => $voting['voting_title'], 'style' => 'width: 100%;']) ?>
        <hr />
        Дата начала голосования: <?= FormatHelpers::formatDate($voting['voting_date_start'], false) ?>
        <br />
        Дата завершения голосования: <?= FormatHelpers::formatDate($voting['voting_date_end'], false) ?>
        <br />
        <?= FormatHelpers::numberOfDaysToFinishVote($voting['voting_date_start'], $voting['voting_date_end']) ?>
        <hr />
        Статус: <?= FormatHelpers::statusNameVoting($voting['status']) ?>
        <hr />
        <?= Html::button('Принять участие', [
                'class' => 'btn btn-primary',
                'id' => 'get-voting-in',
                'data-voting' => $voting['voting_id'],
//                'disabled' => $btn_disabled,
            ]) ?>
    </div>
    <div class="col-md-9">
        <?= $voting['voting_text'] ?>
        <hr />
        <table width="100%">
            <?php foreach ($voting['question'] as $key => $question) : ?>
                <tr>
                    <td colspan="3">
                        <?= $question['questions_text'] ?>
                    </td>
                </tr>
                <tr>
                    <td><?= 'YES' ?></td>
                    <td><?= 'NO' ?></td>
                    <td><?= 'HIT' ?></td>
                </tr>        
            <?php endforeach; ?>
        </table>
        <?php
         var_dump($is_register['status']);
         echo 'HERE ' . ($modal_show);
         
        ?>
    </div>
    
</div>

*/ ?>
<?php if ($is_register['status'] == RegistrationInVoting::STATUS_DISABLED) : ?>

    <?= $this->render('modal/participate-in-voting', [
            'model' => $model,
            'voting_id' => $voting['voting_id']]) ?>

<?php endif; ?>


<?php 
    /* Если кука отправки СМС кода существует сразу же загружаем модальное окно на ввод кода */
    if ($modal_show == true) {
    $this->registerJs("$('#participate-in-voting-" . $voting['voting_id'] . "').modal('show');");    
} 
?>

<?php
$this->registerJs("
    
    function checkDate(){
        var dateNow = " . $_now . ";
        var dateStart = " . $_start . ";
        var _dateStart = '" . FormatHelpers::formatDate($voting['voting_date_start'], true, 1) . "';
        var dateEnd = " . $_end . ";
        var titleModal = '" . $voting['voting_title'] . "';
        var modalMessage = $('#participate_modal-message');
        modalMessage.find('.modal-title').text(titleModal);
        
        if (dateNow < dateStart) {
            modalMessage.find('.modal__text').text('Регистрация на голосование начнется ' + _dateStart);
            modalMessage.modal('show');
            return false;
        } else if (dateNow > dateEnd) {
            modalMessage.find('.modal__text').text('Голосование завершилось');
            modalMessage.modal('show');
            return false;
        }
        
        return true;
    }
    
    $('#get-voting-in').on('click', function(){
        var voting = $(this).data('voting');
        if (checkDate() === true) {
            $.ajax({
              type: 'POST',
              url: 'participate-in-voting',
              data: {voting: voting}
            }).done(function(response) {
                if (response.success === true) {
                    console.log(response.voting_id);
                    $(this).attr('disabled', true);
                    $('#participate-in-voting-" . $voting['voting_id'] . "').modal('show');
                } else if (response.success === false) {
                    console.log(response);
                }
            });
        }
    });
");
?>

<?= ModalWindows::widget(['modal_view' => 'participate_modal']) ?>