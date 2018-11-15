<?php

    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use yii\widgets\Pjax;
    use app\helpers\StatusHelpers;
    
/*
 * Вывод истории платных услуг
 */    
?>

<div class="grid-view">
    
    <?php Pjax::begin() ?>
    
        <?= GridView::widget([
            'dataProvider' => $all_orders,
            'layout' => '{items}{pager}',
            'tableOptions' => [
                'class' => 'table req-table pay-table account-info-table px-0 table-cod',
            ],
            'columns' => [
                [
                    'attribute' => 'services_number',
                    'label' => 'Номер',
                    'value' => 'services_number',
                    'contentOptions' =>[
                        'class' => 'cod-1',
                    ],
                ],
                [
                    'attribute' => 'category_name',
                    'label' => 'Категория',
                    'value' => 'category_name',
                    'contentOptions' =>[
                        'class' => 'cod-2',
                    ],
                ],
                [
                    'attribute' => 'services_name',
                    'label' => 'Наименование услуги',
                    'value' => 'services_name',
                    'contentOptions' =>[
                        'class' => 'cod-3',
                    ],
                ],
                [
                    'attribute' => 'created_at',
                    'label' => 'Дата заявки',
                    'format' => ['date', 'php:d.m.Y'],
                    'contentOptions' =>[
                        'class' => 'cod-4',
                    ],
                ],
                [
                    'attribute' => 'services_comment',
                    'label' => 'Текст заявки',
                    'contentOptions' =>[
                        'class' => 'cod-5',
                    ],
                ],
                [
                    'attribute' => 'services_specialist_id',
                    'label' => 'Исполнитель',
                    'value' => 'services_specialist_id',
                    'contentOptions' =>[
                        'class' => 'cod-6',
                    ],
                ],
                [
                    'attribute' => 'status',
                    'label' => 'Статус',
                    'value' => function ($data) {
                        return StatusHelpers::requestStatus($data['status']);
                    },
                    'contentOptions' =>[
                        'class' => 'cod-7',
                    ],
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'updated_at',
                    'label' => 'Дата закрытия',
                    'format' => ['date', 'php:d.m.Y'],
                    'contentOptions' =>[
                        'class' => 'cod-8',
                    ],
                ],
            ],
        ]); ?>
    
    <?php Pjax::end() ?>
    
</div>