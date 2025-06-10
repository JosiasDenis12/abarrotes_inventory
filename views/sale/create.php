<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Sale */
/* @var $products app\models\Product[] */

$this->title = 'Registrar Venta';
$this->params['breadcrumbs'][] = ['label' => 'Ventas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-create">

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(['id' => 'sale-form']); ?>
            
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'invoice_number')->textInput(['readonly' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'date')->textInput(['type' => 'date', 'value' => date('Y-m-d')]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'payment_method')->dropDownList([
                        'Efectivo' => 'Efectivo',
                        'Tarjeta de Crédito' => 'Tarjeta de Crédito',
                        'Tarjeta de Débito' => 'Tarjeta de Débito',
                        'Transferencia' => 'Transferencia',
                    ], ['prompt' => 'Seleccione método de pago']) ?>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h3>Detalles de la Venta</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Busque productos por nombre o código y seleccione uno de la lista desplegable.
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="products-table">
                            <thead>
                                <tr>
                                    <th width="40%">Producto</th>
                                    <th width="15%">Precio Unitario</th>
                                    <th width="15%">Cantidad</th>
                                    <th width="20%">Total</th>
                                    <th width="10%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row-template">
                                    <td>
                                        <?= Select2::widget([
                                            'name' => 'product_id[]',
                                            'options' => [
                                                'placeholder' => 'Buscar producto...',
                                                'class' => 'product-select',
                                            ],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                'minimumInputLength' => 1,
                                                'ajax' => [
                                                    'url' => Url::to(['sale/product-search']),
                                                    'dataType' => 'json',
                                                    'delay' => 250,
                                                    'data' => new \yii\web\JsExpression('function(params) { 
                                                        console.log("Buscando:", params.term); 
                                                        return {q:params.term}; 
                                                    }'),
                                                    'processResults' => new \yii\web\JsExpression('function(data) {
                                                        console.log("Resultados:", data);
                                                        return data;
                                                    }'),
                                                    'cache' => true
                                                ],
                                            ],
                                        ]) ?>
                                    </td>
                                    <td>
                                        <input type="text" name="price[]" class="form-control price-input" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" class="form-control quantity-input" value="1" min="1">
                                        <small class="stock-info text-muted"></small>
                                    </td>
                                    <td>
                                        <input type="text" name="item_total[]" class="form-control item-total" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-success" id="add-row">
                                            <i class="fas fa-plus"></i> Agregar Producto
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-8">
                            <?= $form->field($model, 'notes')->textarea(['rows' => 3]) ?>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total</label>
                                <?= Html::textInput('total_display', '0.00', [
                                    'class' => 'form-control font-weight-bold text-right',
                                    'readonly' => true,
                                    'id' => 'total-display',
                                    'style' => 'font-size: 1.5rem;',
                                ]) ?>
                                <?= $form->field($model, 'total_amount')->hiddenInput(['id' => 'total-amount'])->label(false) ?>
                            </div>
                            <div class="form-group">
                                <label>Monto Pagado</label>
                                <?= $form->field($model, 'amount_paid')->textInput([
                                    'type' => 'number', 
                                    'step' => '0.01', 
                                    'class' => 'form-control text-right'
                                ])->label(false) ?>
                            </div>
                            <div class="form-group">
                                <label>Cambio</label>
                                <input type="text" id="change-display" class="form-control text-right" readonly value="0.00">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-4">
                <?= Html::submitButton('Guardar Venta', ['class' => 'btn btn-success btn-lg']) ?>
                <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

<?php
// Registrar variables JavaScript
$productSearchUrl = Url::to(['sale/product-search']);
$productInfoUrl = Url::to(['sale/get-product-info']);
$this->registerJsVar('productSearchUrl', $productSearchUrl);
$this->registerJsVar('productInfoUrl', $productInfoUrl);

// Registrar nuestro script personalizado
$this->registerJsFile('@web/js/sale-form.js', ['depends' => [\yii\web\JqueryAsset::class]]);

// Estilos adicionales
$css = <<<CSS
.stock-info {
    display: block;
    margin-top: 5px;
    font-size: 0.8rem;
}
CSS;
$this->registerCss($css);
?>