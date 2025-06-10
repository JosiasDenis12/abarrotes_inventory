<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\YourModel */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Configuración de Perfil';
$this->params['breadcrumbs'][] = ['label' => 'Perfil', 'url' => ['profile/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-form-container">
    
    <!-- Header Section -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header bg-gradient-primary text-white">
            <h3 class="card-title mb-0">
                <i class="fas fa-user-circle mr-2"></i>
                Imagen de Perfil
            </h3>
            <div class="card-tools">
                <small class="text-white-50">Personaliza tu imagen de perfil</small>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Profile Image Preview Section -->
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="profile-image-container mb-4">
                        <div class="position-relative d-inline-block">
                            <?php if ($model->profile_image): ?>
                                <img id="profilePreview" 
                                     src="<?= Yii::getAlias('@web/uploads/' . $model->profile_image) ?>" 
                                     class="profile-image-preview rounded-circle shadow-lg" 
                                     alt="Imagen de perfil">
                            <?php else: ?>
                                <div id="profilePreview" class="profile-image-placeholder rounded-circle shadow-lg">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                    <p class="mt-2 text-muted small">Sin imagen</p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Upload indicator overlay -->
                            <div class="upload-overlay rounded-circle" id="uploadOverlay">
                                <i class="fas fa-camera fa-2x"></i>
                                <p class="small mt-2">Cambiar foto</p>
                            </div>
                        </div>
                        
                        <!-- Current image info -->
                        <?php if ($model->profile_image): ?>
                            <div class="mt-3">
                                <div class="alert alert-info alert-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>Imagen actual:</strong> <?= $model->profile_image ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <!-- Form Section -->
                    <?php $form = ActiveForm::begin([
                        'id' => 'profile-image-form',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                            'class' => 'needs-validation',
                            'novalidate' => true
                        ],
                        'fieldConfig' => [
                            'template' => "<div class=\"form-group\">{label}\n{input}\n{error}\n{hint}</div>",
                            'labelOptions' => ['class' => 'control-label font-weight-bold'],
                            'inputOptions' => ['class' => 'form-control'],
                            'errorOptions' => ['class' => 'invalid-feedback d-block'],
                        ]
                    ]); ?>

                    <!-- Custom File Input -->
                    <div class="form-group">
                        <?= Html::label('Seleccionar Nueva Imagen', 'profile_image', [
                            'class' => 'control-label font-weight-bold'
                        ]) ?>
                        
                        <div class="custom-file-container">
                            <?= $form->field($model, 'profile_image', [
                                'template' => '{input}{error}',
                            ])->fileInput([
                                'id' => 'profileImageInput',
                                'class' => 'custom-file-input',
                                'accept' => 'image/*',
                                'data-max-size' => '2048', // 2MB
                            ]) ?>
                            
                            <label class="custom-file-label" for="profileImageInput" id="fileLabel">
                                <i class="fas fa-cloud-upload-alt mr-2"></i>
                                Seleccionar archivo...
                            </label>
                        </div>
                        
                        <div class="upload-progress mt-2" id="uploadProgress" style="display: none;">
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Requirements -->
                    <div class="alert alert-light border-left-primary">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                            Requisitos de la imagen:
                        </h6>
                        <ul class="mb-0 small">
                            <li>Formatos permitidos: JPG, PNG, GIF</li>
                            <li>Tamaño máximo: 2MB</li>
                            <li>Dimensiones recomendadas: 400x400 píxeles</li>
                            <li>La imagen se redimensionará automáticamente</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-group mt-4">
                        <div class="btn-group-custom">
                            <?= Html::submitButton(
                                '<i class="fas fa-save mr-2"></i>Guardar Cambios', 
                                [
                                    'class' => 'btn btn-success btn-lg',
                                    'id' => 'saveButton',
                                    'data-loading-text' => '<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...'
                                ]
                            ) ?>
                            
                            <?php if ($model->profile_image): ?>
                                <?= Html::a(
                                    '<i class="fas fa-trash mr-2"></i>Eliminar Imagen', 
                                    ['profile/delete-image', 'id' => $model->id], 
                                    [
                                        'class' => 'btn btn-outline-danger btn-lg ml-2',
                                        'data-confirm' => '¿Está seguro que desea eliminar la imagen de perfil?',
                                        'data-method' => 'post'
                                    ]
                                ) ?>
                            <?php endif; ?>
                            
                            <?= Html::a(
                                '<i class="fas fa-times mr-2"></i>Cancelar', 
                                ['profile/index'], 
                                ['class' => 'btn btn-outline-secondary btn-lg ml-2']
                            ) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Tips Card -->
    <div class="card card-outline card-info">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-lightbulb mr-2"></i>
                Consejos para una mejor imagen
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-success">
                        <i class="fas fa-check-circle mr-1"></i>
                        Recomendado:
                    </h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success mr-2"></i>Imagen clara y bien iluminada</li>
                        <li><i class="fas fa-check text-success mr-2"></i>Rostro centrado y visible</li>
                        <li><i class="fas fa-check text-success mr-2"></i>Fondo simple y limpio</li>
                        <li><i class="fas fa-check text-success mr-2"></i>Expresión profesional</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-danger">
                        <i class="fas fa-times-circle mr-1"></i>
                        Evitar:
                    </h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-times text-danger mr-2"></i>Imágenes borrosas o pixeladas</li>
                        <li><i class="fas fa-times text-danger mr-2"></i>Múltiples personas en la foto</li>
                        <li><i class="fas fa-times text-danger mr-2"></i>Objetos que cubran el rostro</li>
                        <li><i class="fas fa-times text-danger mr-2"></i>Imágenes demasiado oscuras</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos CSS personalizados */
.profile-form-container {
    max-width: 900px;
    margin: 0 auto;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.border-left-primary {
    border-left: 4px solid #007bff !important;
}

.profile-image-preview {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 4px solid #007bff;
    transition: all 0.3s ease;
}

.profile-image-placeholder {
    width: 150px;
    height: 150px;
    background: #f8f9fa;
    border: 3px dashed #dee2e6;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.profile-image-container {
    position: relative;
}

.profile-image-container:hover .upload-overlay {
    opacity: 1;
}

.profile-image-container:hover .profile-image-preview,
.profile-image-container:hover .profile-image-placeholder {
    transform: scale(1.05);
    filter: brightness(0.8);
}

.upload-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 123, 255, 0.8);
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    cursor: pointer;
}

.custom-file-container {
    position: relative;
    display: block;
    width: 100%;
    margin-bottom: 1rem;
}

.custom-file-input {
    position: absolute;
    z-index: 2;
    width: 100%;
    height: calc(2.25rem + 2px);
    margin: 0;
    opacity: 0;
    cursor: pointer;
}

.custom-file-label {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    z-index: 1;
    height: calc(2.25rem + 2px);
    padding: 0.375rem 0.75rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.custom-file-label:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
}

.custom-file-input:focus ~ .custom-file-label {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-group-custom .btn {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.progress-sm {
    height: 0.5rem;
}

@media (max-width: 768px) {
    .profile-form-container {
        margin: 0 15px;
    }
    
    .btn-group-custom .btn {
        width: 100%;
        margin-right: 0;
    }
    
    .profile-image-preview,
    .profile-image-placeholder {
        width: 120px;
        height: 120px;
    }
}
</style>

<script>
// JavaScript para mejorar la experiencia de usuario
$(document).ready(function() {
    // Preview de imagen antes de subir
    $('#profileImageInput').change(function(e) {
        const file = e.target.files[0];
        const fileLabel = $('#fileLabel');
        const preview = $('#profilePreview');
        
        if (file) {
            // Validar tipo de archivo
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Por favor seleccione un archivo de imagen válido (JPG, PNG, GIF)');
                this.value = '';
                return;
            }
            
            // Validar tamaño (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('El archivo es demasiado grande. El tamaño máximo es 2MB.');
                this.value = '';
                return;
            }
            
            // Actualizar label
            fileLabel.html(`<i class="fas fa-check text-success mr-2"></i>${file.name}`);
            
            // Mostrar preview
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview.is('img')) {
                    preview.attr('src', e.target.result);
                } else {
                    preview.replaceWith(`
                        <img id="profilePreview" 
                             src="${e.target.result}" 
                             class="profile-image-preview rounded-circle shadow-lg" 
                             alt="Vista previa">
                    `);
                }
            };
            reader.readAsDataURL(file);
        } else {
            fileLabel.html('<i class="fas fa-cloud-upload-alt mr-2"></i>Seleccionar archivo...');
        }
    });
    
    // Efecto de carga en el botón
    $('#profile-image-form').submit(function() {
        const saveButton = $('#saveButton');
        const originalText = saveButton.html();
        const loadingText = saveButton.data('loading-text');
        
        saveButton.html(loadingText).prop('disabled', true);
        
        // Simular progreso (en implementación real, usar eventos AJAX)
        let progress = 0;
        const progressBar = $('#uploadProgress');
        progressBar.show();
        
        const interval = setInterval(function() {
            progress += 10;
            progressBar.find('.progress-bar').css('width', progress + '%');
            
            if (progress >= 100) {
                clearInterval(interval);
            }
        }, 100);
        
        // Restaurar botón después de 3 segundos (para demo)
        setTimeout(function() {
            saveButton.html(originalText).prop('disabled', false);
            progressBar.hide();
        }, 3000);
    });
    
    // Drag & Drop functionality
    const dropZone = $('.upload-overlay');
    const fileInput = $('#profileImageInput');
    
    dropZone.on('click', function() {
        fileInput.click();
    });
    
    // Prevenir comportamiento por defecto del drag & drop
    $(document).on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });
    
    $(document).on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });
    
    // Manejar drop en la zona de upload
    dropZone.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('border-primary');
    });
    
    dropZone.on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('border-primary');
    });
    
    dropZone.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('border-primary');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            fileInput[0].files = files;
            fileInput.trigger('change');
        }
    });
});
</script>