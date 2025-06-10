<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $sales app\models\Sale[] */
/* @var $topProducts array */
/* @var $fromDate string */
/* @var $toDate string */
/* @var $totalSales int */
/* @var $totalAmount float */

$this->title = 'Reporte de Ventas';
$this->params['breadcrumbs'][] = $this->title;

$salesDataProvider = new ArrayDataProvider([
    'allModels' => $sales,
    'pagination' => [
        'pageSize' => 10,
    ],
]);

$topProductsDataProvider = new ArrayDataProvider([
    'allModels' => $topProducts,
    'pagination' => [
        'pageSize' => 10,
    ],
]);
?>
<div class="sale-report">

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <!-- Date Range Selector -->
            <?php $form = ActiveForm::begin(['method' => 'post', 'action' => ['sale/report']]); ?>
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Desde</label>
                        <input type="date" name="from_date" class="form-control" value="<?= $fromDate ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Hasta</label>
                        <input type="date" name="to_date" class="form-control" value="<?= $toDate ?>">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="#" onclick="window.print()" class="btn btn-secondary ml-2">
                        <i class="fas fa-print"></i> Imprimir
                    </a>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Total de Ventas</h5>
                                    <h2><?= $totalSales ?></h2>
                                </div>
                                <div>
                                    <i class="fas fa-shopping-cart fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Monto Total</h5>
                                    <h2>$<?= number_format($totalAmount, 2) ?></h2>
                                </div>
                                <div>
                                    <i class="fas fa-dollar-sign fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products Tab -->
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h3>Productos Más Vendidos</h3>
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $topProductsDataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'name:text:Producto',
                            'code:text:Código',
                            'total_sold:integer:Cantidad Vendida',
                            [
                                'attribute' => 'total_revenue',
                                'label' => 'Ingresos Totales',
                                'format' => 'currency',
                            ],
                        ],
                    ]); ?>
                </div>
            </div>

            <!-- Sales List Tab -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3>Lista de Ventas</h3>
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $salesDataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'invoice_number',
                            'date:date',
                            [
                                'attribute' => 'total_amount',
                                'format' => 'currency',
                            ],
                            'payment_method',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {receipt}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a('<i class="fas fa-eye"></i>', ['sale/view', 'id' => $model->id], [
                                            'title' => 'Ver',
                                            'class' => 'btn btn-sm btn-info',
                                        ]);
                                    },
                                    'receipt' => function ($url, $model) {
                                        return Html::a('<i class="fas fa-print"></i>', ['sale/receipt', 'id' => $model->id], [
                                            'title' => 'Imprimir Ticket',
                                            'class' => 'btn btn-sm btn-secondary',
                                            'target' => '_blank',
                                        ]);
                                    },
                                ],
                                'contentOptions' => ['class' => 'text-center'],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

</div>
