/**
 * JavaScript para el autocompletado de productos en el formulario de ventas
 */
$(document).ready(function() {
    // Inicializar la primera fila
    initializeAutocomplete($(".product-search:first"));
    updateRowTotal($("#row-template"));
    
    // Agregar nueva fila
    $("#add-row").on("click", function() {
        var newRow = $("#row-template").clone();
        newRow.find('input, select').val('');
        newRow.find('.product-select').val('').hide();
        newRow.find('.product-search').val('').show();
        newRow.find('.quantity-input').val(1);
        newRow.find('.stock-info').text('');
        newRow.removeAttr('id');
        $("#products-table tbody").append(newRow);
        
        // Inicializar autocompletado en la nueva fila
        initializeAutocomplete(newRow.find('.product-search'));
    });
    
    // Eliminar fila
    $(document).on("click", ".remove-row", function() {
        var tbody = $("#products-table tbody");
        if (tbody.find("tr").length > 1) {
            $(this).closest("tr").remove();
            updateTotal();
        } else {
            alert("Debe haber al menos una fila de producto.");
        }
    });
    
    // Cambio en la selección de producto (para compatibilidad con el código anterior)
    $(document).on("change", ".product-select", function() {
        var row = $(this).closest("tr");
        var productId = $(this).val();
        
        if (productId) {
            // Mostrar indicador de carga
            row.find(".price-input").val("Cargando...");
            row.find(".stock-info").text("Cargando...");
            
            $.ajax({
                url: productInfoUrl,
                type: 'GET',
                data: { id: productId },
                dataType: 'json',
                success: function(response) {
                    if (!response.error) {
                        row.find(".price-input").val(response.price);
                        row.find(".stock-info").text("Disponible: " + response.stock);
                        row.find(".quantity-input").attr("max", response.stock);
                        updateRowTotal(row);
                    } else {
                        alert("Error: " + response.error);
                        row.find(".price-input").val("");
                        row.find(".item-total").val("");
                        row.find(".stock-info").text("");
                    }
                },
                error: function() {
                    alert("Error al obtener información del producto");
                    row.find(".price-input").val("");
                    row.find(".item-total").val("");
                    row.find(".stock-info").text("");
                }
            });
        } else {
            row.find(".price-input").val("");
            row.find(".item-total").val("");
            row.find(".stock-info").text("");
            updateTotal();
        }
    });
    
    // Inicializar autocompletado
    function initializeAutocomplete(element) {
        element.autocomplete({
            source: searchProductsUrl,
            minLength: 2,
            select: function(event, ui) {
                var row = $(this).closest("tr");
                
                // Establecer el ID del producto en el select oculto
                row.find(".product-select").val(ui.item.id).trigger('change');
                
                // Establecer precio y stock
                row.find(".price-input").val(ui.item.price);
                row.find(".stock-info").text("Disponible: " + ui.item.stock);
                row.find(".quantity-input").attr("max", ui.item.stock);
                
                // Actualizar total
                updateRowTotal(row);
                
                return true;
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li>")
                .append("<div><strong>" + item.value + "</strong><br><small>Precio: $" + 
                        item.price.toFixed(2) + " - Stock: " + item.stock + "</small></div>")
                .appendTo(ul);
        };
    }
    
    // Cambio en la cantidad
    $(document).on("change keyup", ".quantity-input", function() {
        updateRowTotal($(this).closest("tr"));
    });
    
    // Cambio en el precio (por si se modifica manualmente)
    $(document).on("change keyup", ".price-input", function() {
        updateRowTotal($(this).closest("tr"));
    });
    
    // Actualizar total de la fila
    function updateRowTotal(row) {
        var price = parseFloat(row.find(".price-input").val()) || 0;
        var quantity = parseInt(row.find(".quantity-input").val()) || 0;
        var total = price * quantity;
        row.find(".item-total").val(total.toFixed(2));
        updateTotal();
    }
    
    // Actualizar total general
    function updateTotal() {
        var total = 0;
        $(".item-total").each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $("#total-display").val(total.toFixed(2));
        $("#total-amount").val(total.toFixed(2));
        $("#sale-amount_paid").val(total.toFixed(2));
    }
    
    // Validación del formulario antes de enviar
    $("#sale-form").on("submit", function(e) {
        var valid = true;
        var errorMessage = "";
        
        // Verificar si al menos un producto está seleccionado
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
        
        // Verificar si se seleccionó un método de pago
        if (!$("#sale-payment_method").val()) {
            errorMessage += "Debe seleccionar un método de pago.\n";
            valid = false;
        }
        
        // Verificar si el total es mayor que cero
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