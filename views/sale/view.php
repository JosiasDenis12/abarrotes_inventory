<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sale */
/* @var $saleItems app\models\SaleItem[] */

$this->title = 'Detalle de Venta #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ventas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-view">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'date:date',
                            'payment_method',
                            [
                                'attribute' => 'total_amount',
                                'value' => function ($model) {
                                    return '$' . number_format($model->total_amount, 2);
                                },
                            ],
                            [
                                'attribute' => 'amount_paid',
                                'value' => function ($model) {
                                    return '$' . number_format($model->amount_paid, 2);
                                },
                            ],
                            [
                                'label' => 'Cambio',
                                'value' => function ($model) {
                                    return '$' . number_format($model->amount_paid - $model->total_amount, 2);
                                },
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

            <h3 class="mt-4">Productos vendidos</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($saleItems)): ?>
                            <?php foreach ($saleItems as $item): ?>
                                <tr>
                                    <td><?= Html::encode($item->product ? $item->product->name : 'Producto no encontrado') ?></td>
                                    <td><?= $item->quantity ?></td>
                                    <td>$<?= number_format($item->unit_price, 2) ?></td>
                                    <td>$<?= number_format($item->total_price, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay productos registrados en esta venta</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Volver a ventas', ['index'], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<i class="fas fa-print"></i> Imprimir Ticket', ['receipt', 'id' => $model->id], ['class' => 'btn btn-secondary', 'target' => '_blank']) ?>
            </div>
        </div>
    </div>
</div>