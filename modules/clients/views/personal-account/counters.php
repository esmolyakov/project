<?php

    use yii\helpers\Html;
    

/* 
 * Приборы учета
 */

$this->title = 'Приборы учета';
?>

<!--<div class="table-metering-devices">
    <table class="table-metering-devices-water">
        <tr>
            <td class="col-0-metering"></td>
            <th class="col-1-metering">ПРИБОРЫ УЧЕТА</th>
            <th class="col-2-metering">СЛ. ПРОВЕРКА</th>
            <th class="col-3-metering">ДАТА СНЯТИЯ ПОКАЗАНИЙ</th>
            <th class="col-4-metering">ПРЕД. ПОКАЗАНИЯ</th>
            <th class="col-5-metering">ДАТА СНЯТИЯ ТЕКУЩИХ ПОКАЗАНИЙ</th>
            <th class="col-6-metering">ТЕКУЩИЕ ПОКАЗАНИЯ</th>
            <th class="col-7-metering">РАСХОД</th>
        </tr>
        <tr>
            <td class="col-0-metering-org img-top"><img src="assets/img/water%20-hold.svg"></td>
            <td class="col-1-metering-org">Счетчик ХВC<p class="number-pribor">18848646</p></td>
            <td class="col-2-metering-org">1 Ноября 2018г.</td>
            <td class="col-3-metering-org">1 Ноября 2018г.</td>
            <td class="col-4-metering-org">407.0000</td>
            <td class="col-5-metering-org"><p class="to-block">Ввод показаний заблокирован</p></td>
            <td class="col-6-metering-org">
                <form class="table-svg-new">
                <button type="text" class="indication-heat btn-heat"> заказать поверку</button></td>
            <td class="col-7-metering-org img-top3">407.0000</td>
        </tr>
        <tr>
            <td class="col-0-metering img-top"><img src="assets/img/hot-water.svg"></td>
            <td class="col-1-metering">Счетчик ГВC<p class="number-pribor1">18848646</p></td>
            <td class="col-2-metering">1 Ноября 2018г.</td>
            <td class="col-3-metering">1 Ноября 2018г.</td>
            <td class="col-4-metering">311.0000</td>
            <td class="col-5-metering">10 Августа 2018г.</td>
            <td class="col-6-metering">
                <form class="table-svg">
                <input type="text" class="indication-heat"</form></td>
            <td class="col-7-metering img-top3">407.0000</td>
        </tr>
        <tr>
            <td class="col-0-metering-org1 img-top"><img src="assets/img/electro.svg"></td>
            <td class="col-1-metering-org1">Элекросчетчик<p class="number-pribor1">18853797к</p></td>
            <td class="col-2-metering-org1">3 Октября 2018г.</td>
            <td class="col-3-metering-org1">1 Ноября 2018г.</td>
            <td class="col-4-metering-org1">311.0000</td>
            <td class="col-5-metering-org1">10 Августа 2018г.</td>
            <td class="col-6-metering-org1">
                <form class="table-svg">
                <input type="text" class="indication-heat"</form></td>
            <td class="col-7-metering-org1 img-top3">407.0000</td>
        </tr>
        <tr>
            <td class="col-0-metering img-top"><img src="assets/img/heat.svg"></td>
            <td class="col-1-metering">Счетчик отопления<p class="number-pribor1">1885191960к</p></td>
            <td class="col-2-metering">10 сентября 2018г.</td>
            <td class="col-3-metering">1 Ноября 2018г.</td>
            <td class="col-4-metering">311.0000</td>
            <td class="col-5-metering">10 Августа 2018г.</td>
            <td class="col-6-metering">
                <form class="table-svg">
                <input type="text" class="indication-heat"</form></td>
            <td class="col-7-metering img-top3">407.0000</td>
        </tr>
        <tr>
            <td class="col-0-metering-org1 img-top"><img src="assets/img/heat.svg"></td>
            <td class="col-1-metering-org1">Распределитель тепла<p class="number-pribor1">02455782</p></td>
            <td class="col-2-metering-org1">10 сентября 2018г.</td>
            <td class="col-3-metering-org1">1 Ноября 2018г.</td>
            <td class="col-4-metering-org1">311.0000</td>
            <td class="col-5-metering-org1">10 Августа 2018г.</td>
            <td class="col-6-metering-org1">
                <form class="table-svg">
                <input type="text" class="indication-heat"</form></td>
            <td class="col-7-metering-org1 img-top3">407.0000</td>
        </tr>
    </table>

</div>
<div class="lorem-ipsum">
    <h2>Обратите внимание на Lorem ipsum</h2>
    <p>
        #TODO
    </p>
</div>-->

<?php /*
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>

    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon">Месяц</span>
            
            <?= Html::dropDownList('_list-account-all', null, ['1' => 'ЯНВ 2018'], [
                    'class' => 'form-control _list-account-all',
                    'prompt' => 'Выбрать период из списка...'
                ])
            ?>
        </div>
    </div>    
    
    <div class="col-md-12">
        <?= Html::beginForm([
            'id' => 'indication-form',
            'method' => 'POST',
        ]) ?>
            <?= $this->render('data/grid', ['counters' => $counters]) ?>
            
            <!-- Блок условных обозначений для таблицы  -->
            <br />
            <span class="glyphicon glyphicon-flash"></span> - Вы не указали показания приборов учета в текущем месяце
            <br />
        
            <div class="text-right">
                <?= Html::button('Ввести показания', ['class' => 'btn btn-primary btn__add_indication']) ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
            
        <?= Html::endForm(); ?>
        
    </div>
    
</div>
 * 
 * 
 */ ?>

<?php
/* Фильтр заявок пользователя по 
 * ID лицевого счета, типу и статусу заявки
 */
$this->registerJs("    
    $('.current__account_list').on('change', function(e) {
        e.preventDefault();
        var account_id = $('.current__account_list').val();
        
        $.ajax({
            url: 'filter-by-account?account_id=' + account_id,
            method: 'POST',
            data: {
                account_id: account_id,
            },
            success: function(response) {
                if (response.status === false) {
                    $('.grid-counters').html('Возникла ошибка при передаче данных. Обновите страницу, нажав на клавиатуре клавишу F5');
                } else {
                    $('.grid-counters').html(response.data);
                }
            }
        });
    });
");
?>