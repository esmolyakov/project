<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\managers\widgets\SwitchStatusRequest;
    use app\modules\managers\widgets\AddEmployee;

/* 
 * Просмотр и редактирование заявки на платную услугу
 */
$this->title = 'Заявка №' . $paid_request['number'];
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $paid_request['category'] ?>
            </div>
            <div class="panel-body">
                
                <div class="col-md-12">
                    <?= $paid_request['services_name'] ?>
                    <br />
                    <?= FormatHelpers::formatDate($paid_request['date_cr']) ?> 
                    <div id="star" data-request="<?= $paid_request['id'] ?>" data-score-reguest="<?= $paid_request['grade'] ?>"></div>
                    <hr />
                </div>
                
                <div class="col-md-4">
                    <span class="label label-warning">
                        <?= $paid_request['number'] ?>
                    </span>
                </div>
                <div class="col-md-8">
                    
                    <?= SwitchStatusRequest::widget([
                            'view_name' => 'paid_request',
                            'status' => $paid_request['status'],
                            'request_id' => $paid_request['id']]) ?>
                    
                    <?= FormatHelpers::formatDate($paid_request['date_up']) ?>   
                    <hr />                 
                </div>
                
                <div class="col-md-12">
                    <?= $paid_request['text'] ?>
                    <hr />
                </div>
                
                <div class="col-md-6">
                    <?= FormatHelpers::formatFullAdress(
                            $paid_request['town'], 
                            $paid_request['street'], 
                            $paid_request['number_house'], 
                            $paid_request['porch'], 
                            $paid_request['floor'], 
                            $paid_request['flat']) ?>
                </div>
                <div class="col-md-6">
                    <?= $paid_request['phone'] ?>
                    <br />
                    <?= FormatFullNameUser::fullNameByPhone($paid_request['phone']) ?>
                </div>
                
                <div class="clearfix"></div>
                <hr />
                
                <div class="col-md-12 text-center">
                    <div class="col-md-4">
                        <div id="dispatcher-name">
                            <?= FormatFullNameUser::fullNameEmployer($paid_request['dispatcher'], true, true) ?>
                        </div>
                        <br/>
                        <?= Html::button('Назначить диспетчера', [
                            'class' => 'btn btn-default btn-dispatcher',
                            'data-type-request' => 'paid-request',
                            'data-employee' => $paid_request['dispatcher'],
                            'data-target' => '#add-dispatcher-modal',
                            'data-toggle' => 'modal']) ?>
                    </div>
                    <div class="col-md-4">
                        <div id="specialist-name">
                            <?= FormatFullNameUser::fullNameEmployer($paid_request['specialist'], false, true) ?>
                        </div>
                        <br/>
                        <?= Html::button('Назначить специалиста', [
                            'class' => 'btn btn-default',
                            'data-type-request' => 'paid-request',
                            'data-employee' => $paid_request['specialist'],
                            'data-target' => '#add-specialist-modal',
                            'data-toggle' => 'modal']) ?>
                    </div>
                    <div class="col-md-4">
                        <br />
                        <?= Html::button('Отклонить', ['class' => 'btn btn-danger reject-request']) ?>
                    </div>
                </div>
                
            </div>
        </div>        
    </div>
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">Комменатрии к заявке</div>
            <div class="panel-body">
                <?= $this->render('comments/view', [
                    'model' => $model_comment,
                    'comments_find' => $comments_find]) ?>
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    <?= AddEmployee::widget() ?>
</div>


<?php
$grade = $paid_request['grade'] ? $paid_request['grade'] : 0; 
$this->registerJs("
$('div#star').raty({
    score: " . $grade . ",
    readOnly: true,
});    
")
?> 