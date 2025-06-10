<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Sale */
/* @var $form yii\widgets\ActiveForm */
/* @var $products app\models\Product[] */

// Format products for dropdown
$productsList = [];
foreach ($products as $product) {
    $productsList[$product->id] = $product->name . ' (' . $product->code . ') - $' . number_format($product->price, 2);
}

$productInfoUrl = Url::to(['get-product-info']);
?>

<div class="sale-form">

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
            ]) ?>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-success text-white">
            <h3>Detalles de la Venta</h3>
        </div>
        <div class="card-body">
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
                                <?= Html::dropDownList('product_id[]', null, $productsList, [
                                    'class' => 'form-control product-select',
                                    'prompt' => 'Seleccionar producto',
                                ]) ?>
                            </td>
                            <td>
                                <?= Html::textInput('price[]', null, [
                                    'class' => 'form-control price-input',
                                    'readonly' => true,
                                ]) ?>
                            </td>
                            <td>
                                <?= Html::textInput('quantity[]', 1, [
                                    'class' => 'form-control quantity-input',
                                    'type' => 'number',
                                    'min' => 1,
                                ]) ?>
                                <small class="stock-info text-muted"></small>
                            </td>
                            <td>
                                <?= Html::textInput('item_total[]', null, [
                                    'class' => 'form-control item-total',
                                    'readonly' => true,
                                ]) ?>
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
                        <label><strong>Total de la Venta</strong> (calculado automáticamente)</label>
                        <?= Html::textInput('total_display', '0.00', [
                            'class' => 'form-control font-weight-bold text-right bg-light',
                            'readonly' => true,
                            'id' => 'total-display',
                            'style' => 'font-size: 1.5rem;',
                        ]) ?>
                        <?= $form->field($model, 'total_amount')->hiddenInput(['id' => 'total-amount'])->label(false) ?>
                    </div>
                    <div class="form-group">
                        <label><strong>Monto Pagado</strong> (puede modificarse)</label>
                        <?= $form->field($model, 'amount_paid')->textInput([
                            'type' => 'number', 
                            'step' => '0.01', 
                            'class' => 'form-control text-right',
                            'id' => 'sale-amount_paid'
                        ])->label(false) ?>
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

<?php
$js = <<<JS
$(document).ready(function() {
    // Initialize first row
    updateRowTotal($("#row-template"));
    
    // Add new row
    $("#add-row").on("click", function() {
        var newRow = $("#row-template").clone();
        newRow.find('input, select').val('');
        newRow.find('.quantity-input').val(1);
        newRow.find('.stock-info').text('');
        newRow.removeAttr('id');
        $("#products-table tbody").append(newRow);
    });
    
    // Remove row
    $(document).on("click", ".remove-row", function() {
        var tbody = $("#products-table tbody");
        if (tbody.find("tr").length > 1) {
            $(this).closest("tr").remove();
            updateTotal();
        } else {
            alert("Debe haber al menos una fila de producto.");
        }
    });
    
    // Product selection change
    $(document).on("change", ".product-select", function() {
        var row = $(this).closest("tr");
        var productId = $(this).val();
        
        if (productId) {
            $.get("{$productInfoUrl}", { id: productId }, function(data) {
                var response = JSON.parse(data);
                if (!response.error) {
                    row.find(".price-input").val(response.price);
                    row.find(".stock-info").text("Disponible: " + response.stock);
                    row.find(".quantity-input").attr("max", response.stock);
                    updateRowTotal(row);
                }
            });
        } else {
            row.find(".price-input").val("");
            row.find(".item-total").val("");
            row.find(".stock-info").text("");
            updateTotal();
        }
    });
    
    // Quantity change
    $(document).on("change keyup", ".quantity-input", function() {
        updateRowTotal($(this).closest("tr"));
    });
    
    // Update row total
    function updateRowTotal(row) {
        var price = parseFloat(row.find(".price-input").val()) || 0;
        var quantity = parseInt(row.find(".quantity-input").val()) || 0;
        var total = price * quantity;
        row.find(".item-total").val(total.toFixed(2));
        updateTotal();
    }
    
    // Update grand total
    function updateTotal() {
        var total = 0;
        $(".item-total").each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $("#total-display").val(total.toFixed(2));
        $("#total-amount").val(total.toFixed(2));
        $("#sale-amount_paid").val(total.toFixed(2));
    }
    
    // Form submission validation
    $("#sale-form").on("submit", function(e) {
        var valid = true;
        var errorMessage = "";
        
        // Check if at least one product is selected
        var productSelected = false;
        $(".product-select").each(function() {
            if ($(this).val()) {
                productSelected = true;
                return false;
            }
        });
        
        if (!productSelected) {
            errorMessage += "Debe seleccionar al menos un producto.\n";
            valid = false;
        }
        
        // Check if payment method is selected
        if (!$("#sale-payment_method").val()) {
            errorMessage += "Debe seleccionar un método de pago.\n";
            valid = false;
        }
        
        // Check if total amount is greater than zero
        var totalAmount = parseFloat($("#total-amount").val()) || 0;
        if (totalAmount <= 0) {
            errorMessage += "El total de la venta debe ser mayor a cero.\n";
            valid = false;
        }
        
        if (!valid) {
            alert(errorMessage);
            e.preventDefault();
            return false;
        }
        
        return true;
    });
});
JS;

$this->registerJs($js, View::POS_END);
?>