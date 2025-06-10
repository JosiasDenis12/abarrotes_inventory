<?php
// filepath: c:\wamp64\www\abarrotes_inventory\views\site\export.php
?>
<h1>Exportar datos</h1>
<p>Esta es la vista de exportación.</p>

<!-- Filtros y Configuración -->
<div class="card mb-3">
    <div class="card-header bg-info text-white">
        <strong>Filtros y Configuración</strong>
    </div>
    <div class="card-body">
        <label for="tipo-exportacion"><b>¿Qué deseas exportar?</b></label>
        <select id="tipo-exportacion" class="form-control mb-4" style="max-width:300px;">
            <option value="ventas">Ventas</option>
            <option value="productos">Productos</option>
            <option value="proveedores">Proveedores</option>
        </select>

        <div>
            <h4 class="mb-4"><i class="fas fa-file-export"></i> Seleccionar Formato de Exportación</h4>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="export-card export-csv selected" id="card-csv">
                        <div class="export-icon bg-success text-white mb-2"><i class="fas fa-file-csv fa-2x"></i></div>
                        <h5>CSV</h5>
                        <div class="text-muted mb-2">Ideal para análisis y edición</div>
                        <ul class="list-unstyled small mb-3">
                            <li class="text-success">• Hojas múltiples</li>
                            <li class="text-success">• Formato avanzado</li>
                        </ul>
                        <div class="export-group" id="export-csv-group">
                            <a href="<?= \yii\helpers\Url::to(['site/export-csv']) ?>" class="btn btn-success btn-block export-ventas">Exportar Ventas</a>
                            <a href="<?= \yii\helpers\Url::to(['site/export-productos-csv']) ?>" class="btn btn-success btn-block export-productos" style="display:none;">Exportar Productos</a>
                            <a href="<?= \yii\helpers\Url::to(['site/export-proveedores-csv']) ?>" class="btn btn-success btn-block export-proveedores" style="display:none;">Exportar Proveedores</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="export-card export-pdf" id="card-pdf">
                        <div class="export-icon bg-danger text-white mb-2"><i class="fas fa-file-pdf fa-2x"></i></div>
                        <h5>PDF (.pdf)</h5>
                        <div class="text-muted mb-2">Para reportes e impresión</div>
                        <ul class="list-unstyled small mb-3">
                            <li class="text-primary">• Formato fijo</li>
                            <li class="text-primary">• Fácil compartir</li>
                        </ul>
                        <div class="export-group" id="export-pdf-group">
                            <a href="<?= \yii\helpers\Url::to(['site/export-pdf']) ?>" class="btn btn-danger btn-block export-ventas">Exportar Ventas</a>
                            <a href="<?= \yii\helpers\Url::to(['site/export-productos-pdf']) ?>" class="btn btn-danger btn-block export-productos" style="display:none;">Exportar Productos</a>
                            <a href="<?= \yii\helpers\Url::to(['site/export-proveedores-pdf']) ?>" class="btn btn-danger btn-block export-proveedores" style="display:none;">Exportar Proveedores</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$('#tipo-exportacion').on('change', function() {
    var tipo = $(this).val();
    $('.export-ventas, .export-productos, .export-proveedores').hide();
    if(tipo === 'ventas') {
        $('.export-ventas').show();
    } else if(tipo === 'productos') {
        $('.export-productos').show();
    } else if(tipo === 'proveedores') {
        $('.export-proveedores').show();
    }
});
$('.export-card').on('click', function() {
    $('.export-card').removeClass('selected');
    $(this).addClass('selected');
});
JS;
$this->registerJs($js);

$css = <<<CSS
.export-card {
    border: 2px solid #e0e0e0;
    border-radius: 16px;
    padding: 24px 16px;
    text-align: center;
    background: #f8f9fa;
    cursor: pointer;
    transition: box-shadow 0.2s, border-color 0.2s;
    margin-bottom: 16px;
}
.export-card.selected, .export-card:hover {
    border-color: #4f8cff;
    box-shadow: 0 4px 24px rgba(79,140,255,0.08);
    background: #f0f7ff;
}
.export-icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin: 0 auto 12px auto;
    font-size: 2rem;
}
CSS;
$this->registerCss($css);
?>