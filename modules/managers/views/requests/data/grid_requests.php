<?php

    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;

/*
 * Вывод таблицы заявки пользователя
 */
?>
<div class="grid-view">
    <?= GridView::widget([
        'dataProvider' => $requests,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'number',
                'header' => 'Номер заявки',
            ],
            [
                'attribute' => 'category',
                'header' => 'Вид',
            ],
            [
                'attribute' => 'second_name',
                'header' => 'Адрес',
                'value' => function ($data) {
                    return FormatHelpers::formatFullAdress(
                            $data['town'], 
                            $data['street'], 
                            $data['house'], 
                            $data['porch'], 
                            $data['floor'], 
                            $data['flat']);
                }
            ],
            [
                'attribute' => 'comment',
                'header' => 'Описание',
            ],
            [
                'attribute' => 'name_d',
                'header' => 'Диспетчер',
                'value' => function ($data) {
                    return FormatHelpers::formatFullUserName($data['surname_d'], $data['name_d'], $data['sname_d']);
                },
            ],
            [
                'attribute' => 'name_s',
                'header' => 'Специалист',
                'value' => function ($data) {
                    return FormatHelpers::formatFullUserName($data['surname_s'], $data['name_s'], $data['sname_s']);
                },
            ],
            [
                'attribute' => 'date_create',
                'header' => 'Дата создания',
                'value' => function ($date){
                    return FormatHelpers::formatDate($date['date_create']);
                },
            ],
            [
                'attribute' => 'date_close',
                'header' => 'Дата закрытия',
                'value' => function ($date){
                    return FormatHelpers::formatDate($date['date_close']);
                },
            ],
            [
                'attribute' => 'status',
                'header' => 'Статус',
                'value' => function ($data) {
                    return FormatHelpers::statusName($data['status']);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view-request} {delete-request}',
                'buttons' => [
                    'view-request' => function ($url, $data) {                        
                        return 
                            Html::a('Просмотр', 
                                    [
                                        'requests/view-request',
                                        'request_number' => $data['number'],
                                    ], 
                                    [
                                        'data-pjax' => false,
                                        'class' => 'btn btn-info btn-sm',
                                    ]
                            );
                    },
                    'delete-request' => function ($url, $data) {
                        return 
                            Html::button('Удалить', [
                                'data-pjax' => false,
                                'class' => 'btn btn-danger btn-sm delete_dispatcher',
                                'data-target' => '#delete_disp_manager',
                                'data-toggle' => 'modal',
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>