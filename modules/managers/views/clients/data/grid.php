<?php
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;
    use app\modules\managers\components\BlockClientColumn;

/*
 * Вывод таблицы зарегистрированных собственников
 */
?>
    <?= GridView::widget([
        'dataProvider' => $client_list,
        'filterUrl' => Url::to(['requests/index']),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'surname',
                'header' => 'Фамилия',
            ],
            [
                'attribute' => 'name',
                'header' => 'Имя',
            ],
            [
                'attribute' => 'second_name',
                'header' => 'Отчество',
            ],
            [
                'attribute' => 'adress',
                'header' => 'Адрес',
                'value' => function ($data) {
                    return FormatHelpers::formatFullAdress(
                            $data['town'], 
                            $data['street'], 
                            $data['house'], 
                            false, 
                            false, 
                            $data['flat']);
                },
            ],
            [
                'attribute' => 'number',
                'header' => 'Лицевой счет',
            ],
            [
                'attribute' => 'balance',
                'header' => 'Баланс',
                'value' => function ($data) {
                    return FormatHelpers::formatBalance($data['balance']);
                },
                'format' => 'raw',
            ],
            [
                'class' => BlockClientColumn::className(),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view-client} {delete-client}',
                'buttons' => [
                    'view-client' => function ($url, $data) {
                        return 
                            Html::a('Просмотр', 
                                    [
                                        'clients/view-client',
                                        'client_id' => $data['client_id'],
                                        'account_number' => $data['number'],
                                    ], 
                                    [
                                        'data-pjax' => false,
                                        'class' => 'btn btn-info btn-sm',
                                    ]
                            );
                    },
                    'delete-client' => function ($url, $data) {
                        return 
                            Html::a('Удалить', ['clients/delete-client', 'client_id' => $data['client_id']], [
                                'data-pjax' => false,
                                'class' => 'btn btn-danger btn-sm',
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>