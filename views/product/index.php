<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gestión de Productos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
                    <?php if (Yii::$app->user->can('create_product')): ?>
                        <?= Html::a('<i class="fas fa-plus"></i> Crear Producto', ['create'], ['class' => 'btn btn-success']) ?>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'image',
                                    'format' => 'html',
                                    'value' => function ($model) {
                                        if ($model->image) {
                                            return Html::img(Url::to('@web/uploads/products/' . $model->image), 
                                                ['class' => 'img-thumbnail', 'style' => 'width: 80px; height: 80px;']);
                                        } else {
                                            return Html::img(Url::to('@web/img/no-image.png'), 
                                                ['class' => 'img-thumbnail', 'style' => 'width: 80px; height: 80px;']);
                                        }
                                    },
                                ],
                                'name',
                                'code',
                                [
                                    'attribute' => 'category_id',
                                    'value' => 'category.name',
                                ],
                                [
                                    'attribute' => 'price',
                                    'format' => ['decimal', 2],
                                    'contentOptions' => ['class' => 'text-right'],
                                ],
                                [
                                    'attribute' => 'stock',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        $color = $model->isLowStock() ? 'danger' : 'success';
                                        return Html::tag('span', $model->stock, ['class' => 'badge badge-' . $color]);
                                    },
                                    'contentOptions' => ['class' => 'text-center'],
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{view} {update} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            return Html::a('<i class="fas fa-eye"></i>', $url, [
                                                'title' => 'Ver',
                                                'class' => 'btn btn-sm btn-info',
                                            ]);
                                        },
                                        'update' => function ($url, $model) {
                                            if (Yii::$app->user->can('update_product')) {
                                                return Html::a('<i class="fas fa-edit"></i>', $url, [
                                                    'title' => 'Editar',
                                                    'class' => 'btn btn-sm btn-primary',
                                                ]);
                                            }
                                            return '';
                                        },
                                        'delete' => function ($url, $model) {
                                            if (Yii::$app->user->can('delete_product')) {
                                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                                    'title' => 'Eliminar',
                                                    'class' => 'btn btn-sm btn-danger',
                                                    'data' => [
                                                        'confirm' => '¿Estás seguro de eliminar este producto?',
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
            </div>
        </div>
    </div>

</div>
