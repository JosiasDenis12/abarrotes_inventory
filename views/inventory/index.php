<?php
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Consulta de Inventario';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<!-- Tarjetas de estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h2><?= $total ?></h2>
                <div>Total Productos</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h2><?= $inStock ?></h2>
                <div>En Stock</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-dark bg-warning">
            <div class="card-body text-center">
                <h2><?= $lowStock ?></h2>
                <div>Bajo Stock</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body text-center">
                <h2><?= $outStock ?></h2>
                <div>Agotados</div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros de búsqueda -->
<div class="card mb-4">
    <div class="card-header"><b>Filtros de Búsqueda</b></div>
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['index'],
        ]); ?>
        <div class="row">
            <div class="col-md-2"><?= $form->field($searchModel, 'code')->textInput(['placeholder' => 'Ej: PROD-001']) ?></div>
            <div class="col-md-2"><?= $form->field($searchModel, 'name')->textInput(['placeholder' => 'Buscar por nombre']) ?></div>
            <div class="col-md-2"><?= $form->field($searchModel, 'category_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Category::find()->all(), 'id', 'name'),
                ['prompt' => 'Todas las categorías']
            ) ?></div>
            <div class="col-md-2"><?= $form->field($searchModel, 'supplier_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Supplier::find()->all(), 'id', 'name'),
                ['prompt' => 'Todos los proveedores']
            ) ?></div>
            <div class="col-md-2"><?= $form->field($searchModel, 'stock')->textInput(['placeholder' => 'Stock']) ?></div>
            <div class="col-md-2 d-flex align-items-end">
                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary mr-2']) ?>
                <?= Html::a('Limpiar', ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<!-- Tabla de resultados -->
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'name',
        [
            'attribute' => 'category_id',
            'value' => function($model) {
                return $model->category ? $model->category->name : '(Sin categoría)';
            },
            'label' => 'Categoría',
        ],
        'stock',
        'min_stock',
        // ...otras columnas...
    ],
]); ?>