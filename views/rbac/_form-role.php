<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\rbac\Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= Html::label('Nombre del Rol', 'role-name', ['class' => 'control-label']) ?>
            <?= Html::textInput('name', null, ['id' => 'role-name', 'class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Descripción', 'role-description', ['class' => 'control-label']) ?>
            <?= Html::textArea('description', null, ['id' => 'role-description', 'class' => 'form-control', 'rows' => 3]) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div> 