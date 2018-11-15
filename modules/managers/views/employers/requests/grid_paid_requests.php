<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Таблица
 * Все не закрытые заявки на платные услуги специалиста
 */

?>
<?php if (isset($paid_services) && $paid_services) : ?>
<table id="myTable">
    <tr class="header">
        <th style="width:20%;">Номер заявки</th>
        <th style="width:30%;">Дата назначения</th>
        <th style="width:10%;">Статус</th>
        <th style="width:10%;"></th>
    </tr>
    <?php foreach ($paid_services as $paid_service) : ?>
        <tr>
            <td><?= $paid_service['services_number'] ?></td>
            <td><?= FormatHelpers::formatDate($paid_service['updated_at']) ?></td>
            <td><?= FormatHelpers::statusName($paid_service['status']) ?></td>
            <td><?= Html::a('см.', ['/', 'id' => $paid_service['services_id']]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>