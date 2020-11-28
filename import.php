<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DrugStockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Suggestions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="drug-stock-index">
    <div class="box box-danger">
    <div class="box-header bg-danger with-border">
                      <h3 class="box-title">The following drugs had spelling errors.</h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'drug',
             [
             'attribute' => 'Drug',
             'value' => 'drug0.name'
             ],
            'unit_price',
            [
             'attribute' => 'Pharmacy',
             'value' => 'pharmacy0.name'
             ],
            'quantity',
            // 'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div></div></div>
