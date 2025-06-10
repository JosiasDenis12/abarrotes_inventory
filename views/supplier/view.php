<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Supplier $model */
/** @var array $products */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Gestión de Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de eliminar este proveedor?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'contact_person',
            'email:email',
            'phone',
            'address:ntext',
            'notes:ntext',
        ],
    ]) ?>

    <?php if (!empty($products)): ?>
        <h3>Productos de este proveedor</h3>
        <ul>
            <?php foreach ($products as $product): ?>
                <li><?= Html::encode($product->name) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>