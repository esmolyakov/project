<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;    
    use app\modules\clients\widgets\RatingRequest;
    use app\models\StatusRequest;

/* 
 * Детали заявки
 */
$this->title = 'Детали заявки';
?>


<div class="req-body-h-container">
    <h5 class="req-body-h row">
        <div class="<?= $request_info['is_accept'] ? 'req-body-rounded check-rounded' : 'req-body-rounded uncheck-rounded' ?>">
            <?= Html::img('/images/clients/check.svg', ['class' => 'req-body-check']) ?>
        </div>
        <p class="req-body-h-txt">Заявка принята</p>
    </h5>
</div>
<div class="row req-body-container mx-0">
    <div class="col-7 request-body-info">
        <h5 class="req-h">
            <?= $request_info['type_requests_name'] ?>
        </h5>
        <h5 class="req-date">
            <?= FormatHelpers::formatDate($request_info['created_at'], true, 0, false) ?>
        </h5>
        <?php if ($request_info['status'] == StatusRequest::STATUS_CLOSE) : ?>        
            <div class="req-rate-star">
                <div class="starrr" id="star1">

                    <?= RatingRequest::widget([
                        '_status' => $request_info['status'], 
                        '_request_id' => $request_info['requests_id'],
                        '_score' => $request_info['requests_grade']]) ?>

                </div>
            </div>
        <?php endif; ?>
        
        <span class="badge badge-darkblue req-darkblue-badge">
            <?= $request_info['requests_ident'] ?>
        </span>
        <span class="req-badge req-badge-new">
            <span class="right-border">
                <?= FormatHelpers::statusName($request_info['status']) ?>
            </span>
            <span>
                <?= FormatHelpers::formatDate($request_info['updated_at'], true, 0, false) ?>
            </span>
        </span>
        <p class="req-body-info-txt">
            <?= $request_info['requests_comment'] ?>
        </p>
        <?php if (isset($all_images)) : ?>
            <?php foreach ($all_images as $image) : ?>
                <?= FormatHelpers::formatUrlFileRequest($image->filePath) ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="row ml-0">
            <div>
                <?= Html::img('/images/clients/house_icon.svg', ['class' => 'payments-profile-icon']) ?>
                <span>
                    #TODO
                </span>
            </div>
            <div>
                <?= Html::img('/images/clients/phone_icon.svg', ['class' => 'payments-profile-icon']) ?>
                <span>
                    <?= $request_info['requests_phone'] ?>
                </span>
            </div>
        </div>
        <?php if ($request_info['status'] == StatusRequest::STATUS_CLOSE) : ?>
            <div class="request-body-rate">
                <?= Html::button('Оценить', ['class' => 'd-block btn blue-outline-btn mx-auto']) ?>
            </div>
        <?php endif; ?>        
    </div>
    
    <div class="col-5 request-body-chat">
        
        <?= $this->render('form/_comment', [
            'model' => $comments, 
            'comments_find' => $comments_find, 
            'request_id' => $request_info['requests_id']
        ]); ?>

    </div>
</div>


<?php /*
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <div class="col-md-7">
        <div class="panel panel-default">
        <div class="panel-heading">Детали заявки № <?= $request_info['requests_ident'] ?></div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Вид заявки</td>
                        <td><?= $request_info['type_requests_name'] ?></td>
                    </tr>
                    <tr>
                        <td>Идентификатор</td>
                        <td><?= $request_info['requests_ident'] ?></td>
                    </tr>
                    <tr>
                        <td>Адрес</td>
                        <td><?= FormatHelpers::formatFullAdress(
                                $request_info['houses_town'], 
                                $request_info['houses_street'], 
                                null, 
                                $request_info['houses_number_house'], 
                                $request_info['houses_flat']) 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Описание</td>
                        <td>
                            <div class="cutstring" data-display="none" data-max-length="70">
                                <?= $request_info['requests_comment'] ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Контактный телефон</td>
                        <td><?= $request_info['requests_phone'] ?></td>
                    </tr>
                    <tr>
                        <td>Статус заявки</td>
                        <td><?= FormatHelpers::statusName($request_info['status']) ?></td>
                    </tr>
                    <tr>
                        <td>Дата закрытия заявки</td>
                        <td><?= $request_info['status'] == 4 ? Yii::$app->formatter->asDatetime($request_info['updated_at'], "php:d.m.Y H:i:s") : '' ?></td>
                    </tr>
                    <tr>
                        <td>Заявка принята</td>
                        <td>
                            <?= $request_info['is_accept'] ? '<span style="color: #5cb85c" class="glyphicon glyphicon-ok"></span>' : '<span style="color: #c1c1c1;" class="glyphicon glyphicon-ok"></span>' ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Оценка</td>
                        <td>
                            <?= RatingRequest::widget([
                                '_status' => $request_info['status'], 
                                '_request_id' => $request_info['requests_id'],
                                '_score' => $request_info['requests_grade']]) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Время создания</td>
                        <td><?= Yii::$app->formatter->asDatetime($request_info['created_at'], 'php:d.m.Y H:i:s') ?></td>
                    </tr>
                    <tr>
                        <td>Время изменения</td>
                        <td><?= Yii::$app->formatter->asDatetime($request_info['updated_at'], 'php:d.m.Y H:i:s') ?></td>
                    </tr>
                    <tr>
                        <td>Прикрепленные файлы</td>
                        <td>
                            <?php if (isset($all_images)) : ?>
                                <?php foreach ($all_images as $image) : ?>
                                    <?= FormatHelpers::formatUrlFileRequest($image->getImagePath($image->filePath)) ?>
                                    <br />
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>            
        </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <?= $this->render('form/_comment', [
            'model' => $comments, 
            'comments_find' => $comments_find, 
            'request_id' => $request_info['requests_id']
        ]); ?>
    </div>
    
</div>
*/ ?>