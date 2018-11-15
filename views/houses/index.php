<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HousesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Houses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="houses-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Houses', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php yii\widgets\Pjax::begin(['id' => 'countries']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'houses_id',
            'houses_name',
            'houses_town',
            'houses_street',
            'houses_number_house',
            //'houses_porch',
            //'houses_floor',
            //'houses_flat',
            //'houses_rooms',
            //'houses_square',
            //'houses_account_id',
            //'houses_client_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php yii\widgets\Pjax::end() ?>
</div>
