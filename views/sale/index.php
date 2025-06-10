<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use Yii;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SaleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gestión de Ventas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-index">

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
            <?= Html::a('<i class="fas fa-plus"></i> Registrar Venta', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'invoice_number',
                        [
                            'attribute' => 'date',
                            'format' => 'date',
                            'filter' => Html::activeTextInput($searchModel, 'date', ['type' => 'date', 'class' => 'form-control']),
                        ],
                        [
                            'attribute' => 'total_amount',
                            'format' => 'currency',
                            'contentOptions' => ['class' => 'text-right'],
                        ],
                        [
                            'attribute' => 'payment_method',
                            'filter' => Html::activeDropDownList($searchModel, 'payment_method', [
                                'Efectivo' => 'Efectivo',
                                'Tarjeta de Crédito' => 'Tarjeta de Crédito',
                                'Tarjeta de Débito' => 'Tarjeta de Débito',
                                'Transferencia' => 'Transferencia',
                            ], ['class' => 'form-control', 'prompt' => 'Todos']),
                        ],
                        [
                            'attribute' => 'created_by',
                            'value' => 'createdBy.username',
                            'filter' => false,
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {receipt} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-eye"></i>', $url, [
                                        'title' => 'Ver',
                                        'class' => 'btn btn-sm btn-info',
                                    ]);
                                },
                                'receipt' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-print"></i>', ['receipt', 'id' => $model->id], [
                                        'title' => 'Imprimir Ticket',
                                        'class' => 'btn btn-sm btn-secondary',
                                        'target' => '_blank',
                                    ]);
                                },
                                'delete' => function ($url, $model) {
                                    if (Yii::$app->user->can('delete_sale')) {
                                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                                            'title' => 'Eliminar',
                                            'class' => 'btn btn-sm btn-danger',
                                            'data' => [
                                                'confirm' => '¿Está seguro que desea eliminar esta venta?',
                                                'method' => 'post',
                                            ],
                                        ]);
                                    }
                                    return '';
                                },
                            ],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::a('<i class="fas fa-chart-bar"></i> Ver Reportes', ['report'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

</div>