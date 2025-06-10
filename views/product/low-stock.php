<?php


use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Productos con Bajo Stock';
$this->params['breadcrumbs'][] = ['label' => 'Gestión de Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-low-stock">

    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
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
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        'min_stock',
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>