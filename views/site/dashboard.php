<?php

/* @var $this yii\web\View */
/* @var $lowStockProducts app\models\Product[] */
/* @var $recentSales app\models\Sale[] */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-dashboard">
    <div class="dashboard-header" style="position:relative; background:url('../img/bg-dashboard.jpg') center center/cover no-repeat;">
        <div class="dashboard-overlay"></div>
        <div class="dashboard-content">
            <h1 class="display-4">Bienvenido al Sistema de Inventario</h1>
            <p class="lead">Abarrotes Tendejosn San Francisco</p>
        </div>
    </div>

    <div class="row">
        <!-- Stats Cards -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Productos</h5>
                            <h2><?= $totalProducts ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-box fa-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="<?= Url::to(['/product/index']) ?>" class="text-white">Ver Productos <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Ventas</h5>
                            <h2><?= $totalSales ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-shopping-cart fa-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="<?= Url::to(['/sale/index']) ?>" class="text-white">Ver Ventas <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Proveedores</h5>
                            <h2><?= $totalSuppliers ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-truck fa-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="<?= Url::to(['/supplier/index']) ?>" class="text-white">Ver Proveedores <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Ventas Totales</h5>
                            <h2>$<?= number_format($salesAmount ?? 0, 2) ?></h2>

                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave fa-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="<?= Url::to(['/sale/report']) ?>" class="text-white">Ver Reportes <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Main Menu Buttons -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3>Menú Principal</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <a href="<?= Url::to(['/product/index']) ?>" class="btn btn-lg btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center p-4">
                                <i class="fas fa-boxes fa-3x mb-3"></i>
                                <span>Gestión de Productos</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= Url::to(['/sale/create']) ?>" class="btn btn-lg btn-outline-success w-100 h-100 d-flex flex-column justify-content-center align-items-center p-4">
                                <i class="fas fa-cash-register fa-3x mb-3"></i>
                                <span>Registrar Venta</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= Url::to(['/inventory/index']) ?>" class="btn btn-lg btn-outline-info w-100 h-100 d-flex flex-column justify-content-center align-items-center p-4">
                                <i class="fas fa-search fa-3x mb-3"></i>
                                <span>Consultar Inventario</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= Url::to(['/supplier/index']) ?>" class="btn btn-lg btn-outline-warning w-100 h-100 d-flex flex-column justify-content-center align-items-center p-4">
                                <i class="fas fa-truck-loading fa-3x mb-3"></i>
                                <span>Gestión de Proveedores</span>
                            </a>
                        </div>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-md-3 mb-3">
                            <a href="<?= Url::to(['/product/low-stock']) ?>" class="btn btn-lg btn-outline-danger w-100 h-100 d-flex flex-column justify-content-center align-items-center p-4">
                                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                <span>Inventario Bajo</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= Url::to(['/sale/report']) ?>" class="btn btn-lg btn-outline-secondary w-100 h-100 d-flex flex-column justify-content-center align-items-center p-4">
                                <i class="fas fa-chart-line fa-3x mb-3"></i>
                                <span>Generar Reportes</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= Url::to(['/site/export']) ?>" class="btn btn-lg btn-outline-dark w-100 h-100 d-flex flex-column justify-content-center align-items-center p-4">
                                <i class="fas fa-file-export fa-3x mb-3"></i>
                                <span>Exportar Datos</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Low Stock Products -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h3 class="m-0">Productos con Bajo Inventario</h3>
                    <a href="<?= Url::to(['/product/low-stock']) ?>" class="btn btn-sm btn-outline-light">Ver Todos</a>
                </div>
                <div class="card-body">
                    <?php if (count($lowStockProducts) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Stock Actual</th>
                                        <th>Stock Mínimo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lowStockProducts as $product): ?>
                                        <tr>
                                            <td><?= $product->name ?></td>
                                            <td><span class="badge badge-danger"><?= $product->stock ?></span></td>
                                            <td><?= $product->min_stock ?></td>
                                            <td>
                                                <a href="<?= Url::to(['/product/view', 'id' => $product->id]) ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success">
                            No hay productos con inventario bajo.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h3 class="m-0">Ventas Recientes</h3>
                    <a href="<?= Url::to(['/sale/index']) ?>" class="btn btn-sm btn-outline-light">Ver Todas</a>
                </div>
                <div class="card-body">
                    <?php if (count($recentSales) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Factura</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentSales as $sale): ?>
                                        <tr>
                                            <td><?= $sale->invoice_number ?></td>
                                            <td><?= Yii::$app->formatter->asDate($sale->date) ?></td>
                                            <td>$<?= number_format($sale->total_amount, 2) ?></td>
                                            <td>
                                                <a href="<?= Url::to(['/sale/view', 'id' => $sale->id]) ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= Url::to(['/sale/receipt', 'id' => $sale->id]) ?>" class="btn btn-sm btn-secondary" target="_blank">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            No hay ventas recientes.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
