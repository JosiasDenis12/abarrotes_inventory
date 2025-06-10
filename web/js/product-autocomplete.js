$(function() {
    // Función para inicializar el autocompletado en un elemento
    function initAutocomplete(element) {
        console.log("Inicializando autocompletado en:", element);

        $(element).autocomplete({
            source: searchProductsUrl,
            minLength: 2,
            select: function(event, ui) {
                console.log("Producto seleccionado:", ui.item);

                var row = $(this).closest("tr");

                // Ocultar el campo de búsqueda y mostrar el producto seleccionado
                $(this).hide();
                row.find(".selected-product-name").text(ui.item.value).show();

                // Establecer el ID del producto en el campo oculto
                row.find(".product-id").val(ui.item.id);

                // Establecer precio y stock
                row.find(".price-input").val(ui.item.price);
                row.find(".stock-info").text("Disponible: " + ui.item.stock);
                row.find(".quantity-input").attr("max", ui.item.stock);

                // Actualizar total
                updateRowTotal(row);

                return false;
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li>")
                .append("<div><strong>" + item.value + "</strong><br><small>Precio: $" +
                        item.price.toFixed(2) + " - Stock: " + item.stock + "</small></div>")
                .appendTo(ul);
        };
    }

    // Inicializar autocompletado en los campos existentes
    $(".product-search").each(function() {
        initAutocomplete(this);
    });

    // Agregar nueva fila
    $("#add-row").on("click", function() {
        var lastRow = $("#products-table tbody tr:last");
        var newRow = lastRow.clone();

        // Limpiar valores
        newRow.find(".product-search").val("").show();
        newRow.find(".selected-product-name").text("").hide();
        newRow.find(".product-id").val("");
        newRow.find(".price-input").val("");
        newRow.find(".quantity-input").val(1);
        newRow.find(".item-total").val("");
        newRow.find(".stock-info").text("");

        // Agregar la nueva fila a la tabla
        $("#products-table tbody").append(newRow);

        // Inicializar autocompletado en el nuevo campo
        initAutocomplete(newRow.find(".product-search"));
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

    // Cambiar producto (botón para cambiar)
    $(document).on("click", ".change-product", function() {
        var row = $(this).closest("tr");
        row.find(".selected-product-name").hide();
        row.find(".product-search").val("").show().focus();
        row.find(".product-id").val("");
        row.find(".price-input").val("");
        row.find(".quantity-input").val(1);
        row.find(".item-total").val("");
        row.find(".stock-info").text("");
        updateTotal();
    });

    // Cambio en la cantidad
    $(document).on("change keyup", ".quantity-input", function() {
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
        $(".product-id").each(function() {
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

    // Mensaje de depuración
    console.log("Script de autocompletado cargado correctamente");
    console.log("URL de búsqueda:", searchProductsUrl);
});