/**
 * JavaScript para la funcionalidad del formulario de ventas
 */
$(function() {
    // Inicializar la primera fila
    updateRowTotal($('#row-template'));

    // Inicializar Select2 en la primera fila
    $('.product-select').select2({
        theme: 'krajee',
        placeholder: 'Buscar producto...',
        allowClear: true,
        ajax: {
            url: productSearchUrl,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        }
    });

    // Manejar el evento de selección para la primera fila
    $('.product-select').on('select2:select', function(e) {
        handleProductSelection(e);
    });

    // Agregar nueva fila
    $('#add-row').on('click', function() {
        var newRow = $('#row-template').clone();
        newRow.removeAttr('id');

        // Elimina el Select2 anterior y crea uno nuevo
        var td = newRow.find('td').eq(0);
        td.find('.select2-container').remove();
        td.find('select').remove();

        // Crea un nuevo select
        var select = $('<select></select>')
            .attr('name', 'product_id[]')
            .addClass('product-select')
            .css('width', '100%');
        td.prepend(select);

        // Inicializa Select2 en el nuevo select
        select.select2({
            theme: 'krajee',
            placeholder: 'Buscar producto...',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: productSearchUrl,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return data;
                },
                cache: true
            }
        });

        // Limpia valores de la fila
        newRow.find('.price-input').val('');
        newRow.find('.quantity-input').val(1);
        newRow.find('.item-total').val('');
        newRow.find('.stock-info').text('');

        // Agrega la nueva fila a la tabla
        $('#products-table tbody').append(newRow);

        // Evento para la nueva fila
        select.on('select2:select', function(e) {
            handleProductSelection(e);
        });
    });

    // Manejar selección de producto
    function handleProductSelection(e) {
        var data = e.params.data;
        var row = $(e.target).closest('tr');

        // Obtener información detallada del producto
        $.get(productInfoUrl, { id: data.id }, function(response) {
            if (!response.error) {
                row.find('.price-input').val(response.price);
                row.find('.stock-info').text('Disponible: ' + response.stock);
                row.find('.quantity-input').attr('max', response.stock);
                updateRowTotal(row);
            } else {
                row.find('.price-input').val('');
                row.find('.stock-info').text('');
                row.find('.quantity-input').attr('max', 1);
                updateRowTotal(row);
            }
        });
    }

    // Eliminar fila
    $(document).on('click', '.remove-row', function() {
        var tbody = $('#products-table tbody');
        if (tbody.find('tr').length > 1) {
            $(this).closest('tr').remove();
            updateTotal();
        } else {
            alert('Debe haber al menos una fila de producto.');
        }
    });

    // Cambio en la cantidad
    $(document).on('change keyup', '.quantity-input', function() {
        updateRowTotal($(this).closest('tr'));
    });

    // Actualizar total de la fila
    function updateRowTotal(row) {
        var price = parseFloat(row.find('.price-input').val()) || 0;
        var quantity = parseInt(row.find('.quantity-input').val()) || 0;
        var total = price * quantity;
        row.find('.item-total').val(total.toFixed(2));
        updateTotal();
    }

    // Actualizar total general
    function updateTotal() {
        var total = 0;
        $('.item-total').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#total-display').val(total.toFixed(2));
        $('#total-amount').val(total.toFixed(2));
        $('#sale-amount_paid').val(total.toFixed(2));
    }

    // Calcular el cambio automáticamente al ingresar el monto pagado
    $('#sale-amount_paid').on('input', function() {
        var pagado = parseFloat($(this).val()) || 0;
        var total = parseFloat($('#total-amount').val()) || 0;
        var cambio = pagado - total;
        $('#change-display').val(cambio >= 0 ? cambio.toFixed(2) : '0.00');
    });

    // Validación del formulario antes de enviar
    $('#sale-form').on('submit', function(e) {
        var valid = true;
        var errorMessage = '';

        // Verificar si al menos un producto está seleccionado
        var productSelected = false;
        $('.product-select').each(function() {
            if ($(this).val()) {
                productSelected = true;
                return false;
            }
        });

        if (!productSelected) {
            errorMessage += 'Debe seleccionar al menos un producto.\n';
            valid = false;
        }

        // Verificar si se seleccionó un método de pago
        if (!$('#sale-payment_method').val()) {
            errorMessage += 'Debe seleccionar un método de pago.\n';
            valid = false;
        }

        // Verificar si el total es mayor que cero
        var totalAmount = parseFloat($('#total-amount').val()) || 0;
        if (totalAmount <= 0) {
            errorMessage += 'El total de la venta debe ser mayor a cero.\n';
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