<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Registro';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h1 class="h3"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($model, 'email') ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'role')->dropDownList([
    'staff' => 'Staff',
    'manager' => 'Manager',
    'guest' => 'Guest',
    // 'admin' => 'Admin', // solo si quieres permitirlo
], ['prompt' => 'Selecciona un rol']) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Registrarse', ['class' => 'btn btn-success btn-block', 'name' => 'signup-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>