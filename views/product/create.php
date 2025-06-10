<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $categories app\models\Category[] */
/* @var $suppliers app\models\Supplier[] */

$this->title = 'Crear Producto';
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'categories' => $categories,
                'suppliers' => $suppliers,
            ]) ?>
        </div>
    </div>

</div>
