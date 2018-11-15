<?php

    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\modules\managers\components\PayServiceColumn;

/* 
 * Таблица
 * Услуги
 */

?>
<div class="grid-view">
    <?= GridView::widget([
        'dataProvider' => $services,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'header' => 'Категория <br /> Наименование услуги',
                'value' => function($data) {
                    return $data['category'] . '<br />' . $data['name'];
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'rate',
                'header' => 'Стоимость',
                'value' => 'rate',
            ],
            [
                'attribute' => 'unit',
                'header' => 'Ед. измерения',
                'value' => 'unit',
            ],
            [
                'class' => PayServiceColumn::className(),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{edit-service} {delete-service}',
                'buttons' => [
                    'edit-service' => function ($url, $data) {                        
                        return 
                            Html::a('Просмотр', 
                                    [
                                        'services/edit-service',
                                        'service_id' => $data['id'],
                                    ], 
                                    [
                                        'data-pjax' => false,
                                        'class' => 'btn btn-info btn-sm',
                                    ]
                            );
                    },
                    'delete-service' => function ($url, $data) {
                        return 
                            Html::button('Удалить', [
                                'data-pjax' => false,
                                'class' => 'btn btn-danger btn-sm delete_service__bnt',
                                'data-target' => '#delete_service',
                                'data-toggle' => 'modal',
                                'data-service' => $data['id'],
                                'data-service-name' => $data['name'],
                            ]);
                        },
                    ],
                ],
        ]
    ]) ?>
</div>