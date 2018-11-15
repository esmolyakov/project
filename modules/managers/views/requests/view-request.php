<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\managers\widgets\SwitchStatusRequest;
    use app\modules\managers\widgets\AddEmployee;

/* 
 * Просмотр и редактирование заявки
 */
$this->title = 'Заявка №' . $request['requests_ident'];
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $request['is_accept'] ? 'Заявка принята' : 'Заявка не принята' ?>
            </div>
            <div class="panel-body">
                
                <div class="col-md-12">
                    <?= $request['type_requests_name'] ?>
                    <br />
                    <?= FormatHelpers::formatDate($request['created_at']) ?> 
                    <div id="star" data-request="<?= $request['requests_id'] ?>" data-score-reguest="<?= $request['requests_grade'] ?>"></div>
                    <hr />
                </div>
                
                <div class="col-md-4">
                    <span class="label label-warning">
                        <?= $request['requests_ident'] ?>
                    </span>
                </div>
                <div class="col-md-8">
                    
                    <?= SwitchStatusRequest::widget([
                            'view_name' => 'request',
                            'status' => $request['status'],
                            'request_id' => $request['requests_id']]) ?>
                    
                    <?= FormatHelpers::formatDate($request['updated_at']) ?>   
                    <hr />                 
                </div>
                
                <div class="col-md-12">
                    <?= $request['requests_comment'] ?>
                    <hr />
                    Прикрепленные файлы: <br />
                    <?php if (isset($all_images)) : ?>
                        <?php foreach ($all_images as $image) : ?>
                            <?= FormatHelpers::formatUrlFileRequest($image->getImagePath($image->filePath)) ?>
                            <br />
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <hr />
                </div>
                
                <div class="col-md-6">
                    <?= FormatHelpers::formatFullAdress(
                            $request['estate_town'], 
                            $request['houses_street'], 
                            $request['houses_number_house'], 
                            $request['flats_porch'], 
                            $request['flats_floor'], 
                            $request['flats_number']) ?>
                </div>
                <div class="col-md-6">
                    <?= $request['requests_phone'] ?>
                    <br />
                    <?= FormatFullNameUser::fullNameByPhone($request['requests_phone']) ?>
                </div>
                
                <div class="clearfix"></div>
                <hr />
                
                <div class="col-md-12 text-center">
                    <div class="col-md-4">
                        <div id="dispatcher-name">
                            <?= FormatFullNameUser::fullNameEmployer($request['requests_dispatcher_id'], true, true) ?>
                        </div>
                        <br/>
                        <?= Html::button('Назначить диспетчера', [
                            'class' => 'btn btn-default btn-dispatcher',
                            'data-type-request' => 'request',
                            'data-employee' => $request['requests_dispatcher_id'],
                            'data-target' => '#add-dispatcher-modal',
                            'data-toggle' => 'modal']) ?>
                    </div>
                    <div class="col-md-4">
                        <div id="specialist-name">
                            <?= FormatFullNameUser::fullNameEmployer($request['requests_specialist_id'], false, true) ?>
                        </div>
                        <br/>
                        <?= Html::button('Назначить специалиста', [
                            'class' => 'btn btn-default',
                            'data-type-request' => 'request',
                            'data-employee' => $request['requests_specialist_id'],
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
$grade = $request['requests_grade'] ? $request['requests_grade'] : 0; 
$this->registerJs("
$('div#star').raty({
    score: " . $grade . ",
    readOnly: true,
});    
")
?> 