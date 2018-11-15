<?php
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\helpers\StatusHelpers;

/*
 * Вывод таблицы заявок текущего пользователя
 */
?>
    <?= GridView::widget([
        'dataProvider' => $all_requests,
        'layout' => '{items}{pager}',
        'tableOptions' => [
            'class' => 'table req-table  pay-table account-info-table px-0 tableBodyScroll',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'requests_ident',
                'value' => function ($data) {
                    return Html::a($data->requests_ident, ['requests/view-request', 'request_numder' => $data->requests_ident]);
                },
                'contentOptions' =>[
                    'class' => 'req-table-description',
                ],
                'format' => 'raw',
            ],
            [
                'attribute' => 'requests_type_id',
                'value' => function ($data) {
                    return $data->getNameRequest();            
                },
                'contentOptions' =>[
                    'class' => 'req-table-description',
                ],        
            ],
            [
                'attribute' => 'requests_comment',
                'value' => function ($data) {
                    return $data->requests_comment
                            . '<br />'
                            . FormatHelpers::imageRequestList($data['image']);
                },
                'contentOptions' =>[
                    'class' => 'req-table-description-request',
                ],
                'format' => 'raw',
            ],                        
            'requests_specialist_id',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y'],
                'contentOptions' =>[
                    'class' => 'date-req-table',
                ],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d.m.Y'],
                'contentOptions' =>[
                    'class' => 'date-req-table',
                ],
            ],
            [
                'attribute' => 'status',
                'label' => 'Статус',
                'value' => function ($data) {
                    return StatusHelpers::requestStatus($data['status'], $data->requests_id);
                },
                'format' => 'raw',
            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//            ],
        ],
    ]); ?>