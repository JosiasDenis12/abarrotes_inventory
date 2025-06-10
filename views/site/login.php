<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Iniciar Sesión';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-container">
    <div class="login-image-side">
        <div class="overlay"></div>
        <div class="image-content">
            <h1>Bienvenido al<br>Sistema de Gestión<br>de Inventario</h1>
            <p>Accede con tu cuenta para administrar productos, ventas, inventario y más.</p>
            <div class="store-info mt-4">
                <p><i class="fas fa-map-marker-alt"></i> C. 49 122-10 y 12, San Francisco, 97783 Valladolid, Yuc.</p>
                <p><i class="fas fa-phone"></i> (52) 985-106-2948</p>
            </div>
        </div>
    </div>
    
    <div class="login-form-side">
        <div class="login-form-wrapper">
            <div class="text-center mb-4">
                <i class="fas fa-lock login-icon"></i>
                <h2>Iniciar Sesión</h2>
            </div>
            
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'form-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback'],
                ],
            ]); ?>

            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Ingrese su usuario'])->label(false) ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Ingrese su contraseña'])->label(false) ?>
            </div>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n{error}",
                'class' => 'custom-control-input',
                'labelOptions' => ['class' => 'custom-control-label'],
            ]) ?>

            <div class="form-group mt-4">
                <?= Html::submitButton('Entrar', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
            
            <div class="text-center mt-3">
                <p>
                    ¿Olvidaste tu contraseña?
                    <a href="<?= \yii\helpers\Url::to(['/site/request-password-reset']) ?>">Recuperarla</a>
                </p>
            </div>
            <div class="col-lg-12 text-center mt-3">
                <p>¿No tienes cuenta? <a href="<?= \yii\helpers\Url::to(['/site/signup']) ?>">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
</div>
