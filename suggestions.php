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
    <?php echo json_encode($suggestions);?>

    </div>
</div>
</div>
