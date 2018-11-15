<?php
    
    use yii\helpers\Html;
    use app\modules\clients\widgets\AlertsShow;
    
/* 
 * Платные заявки (Заказать услугу)
 */
$this->title = 'Заказать услугу';
$category_id = 0;
?>

<?php foreach ($pay_services as $key => $service) : ?>

<?php 
    /*
     *  Если ID текущей категории не равен ID предыдущей
     *  то обнуляем сетку бутстрапа и выводим блок услуг с новой категорией 
     */
    if ($category_id != $service['category']['category_id']) : ?>

    <div class="w-100"></div>
    
<?php endif; ?>

<div class="card services-card-preview box-shadow paid-service-list" data-toggle="modal" data-target=".bd-example-modal-lg">
    <div class="services-card-preview-executor-container">
        <h5 class="services-card-preview-executor">
            <?= $service['category']['category_name'] ?>
        </h5> 
    </div>
    <?= Html::img($service['services_image'], ['class' => 'card-img-top services-card-img-top-preview', 'alt' => $service['services_name']]) ?>
    <h5 class="services-card-preview-h">
        <?= $service['services_name'] ?>
    </h5>
    <div class="card-body m-0 p-0 services-card-preview-body">
        <!--  ограничение на 250 символов -->
        <p class="card-text services-card-preview-text mt-0">
            <?= $service['services_description'] ?>
        </p>
        <div class="services-btn-container">
            <span class="cost_service"><?= $service['services_cost'] ?> &#8381;</span>
            <?= Html::button('Заказать', [
                    'class' => 'btn blue-outline-btn btn-add-servic mx-auto new-rec', 
                    'data-service-cat' => $service['category']['category_id'],
                    'data-service' => $service['services_id']]) ?>
        </div>
        <div class="d-flex justify-content-around align-items-center">
        </div>
    </div>
</div>
<?php $category_id = $service['category']['category_id'] ?>

<?php endforeach; ?>
    
<?= $this->render('form/add-paid-request', [
        'new_order' => $new_order, 
        'name_services_array' => $name_services_array]) ?>
    
<?php
$this->registerJs('
    $(".new-rec").on("click", function(){
        var idService = $(this).data("service");
        var idCategory = $(this).data("service-cat");
        $("#add-record-modal").modal("show");
        $("#add-record-modal").find("#name_services").val(idService);
        $("#secret-name").val(idService);
        $("#secret-cat").val(idCategory);
    });    
')
?>