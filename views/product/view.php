<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
            <div>
                <?php if (Yii::$app->user->can('update_product')): ?>
                    <?= Html::a('<i class="fas fa-edit"></i> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('delete_product')): ?>
                    <?= Html::a('<i class="fas fa-trash"></i> Eliminar', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => '¿Estás seguro de eliminar este producto?',
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center mb-4">
                        <?php if ($model->image): ?>
                            <?= Html::img(Url::to('@web/uploads/products/' . $model->image), 
                                ['class' => 'img-fluid img-thumbnail', 'style' => 'max-height: 300px;']); ?>
                        <?php else: ?>
                            <?= Html::img(Url::to('@web/img/no-image.png'), 
                                ['class' => 'img-fluid img-thumbnail', 'style' => 'max-height: 300px;']); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'name',
                            'code',
                            'description:ntext',
                            [
                                'attribute' => 'category_id',
                                'value' => $model->category ? $model->category->name : 'No asignada',
                            ],
                            [
                                'attribute' => 'supplier_id',
                                'value' => $model->supplier ? $model->supplier->name : 'No asignado',
                            ],
                            [
                                'attribute' => 'price',
                                'value' => function ($model) {
                                    return '$' . number_format($model->price, 2);
                                },
                            ],
                            [
                                'attribute' => 'cost_price',
                                'value' => function ($model) {
                                    return '$' . number_format($model->cost_price, 2);
                                },
                            ],
                            [
                                'attribute' => 'stock',
                                'format' => 'raw',
                                'value' => function($model) {
                                    $color = $model->isLowStock() ? 'danger' : 'success';
                                    return Html::tag('span', $model->stock, ['class' => 'badge badge-' . $color]);
                                },
                            ],
                            'min_stock',
                            'unit',
                            'location',
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>