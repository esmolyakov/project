<?php

    use yii\helpers\Html;

/* 
 * Вид статусов заявок
 */

?>
<?php if (Yii::$app->controller->id == 'requests' && Yii::$app->controller->action->id == 'index') : ?>
    <nav class="navbar navbar-dark justify-content-between navbar-expand-sm p-0 carousel-item d-block pay-panel mx-auto req-nav">
        <ul class="nav nav-pills mx-auto justify-content-start px-3">
            <li class="nav-item">
                <?= Html::a('Все Заявки', ['/'], ['class' => 'btn req-btn req-btn-all status_request-switch', 'data-status' => '-1']) ?>
            </li>
            <?php foreach ($status_requests as $key => $status) : ?>
                <li class="nav-item">
                    <?= Html::a($status, ['requests/index'], ['class' => $css_classes[$key] . ' status_request-switch', 'data-status' => $key]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>