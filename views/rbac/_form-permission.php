<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\rbac\Permission */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-primary">
    <div class="box-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= Html::label('Nombre del Permiso', 'permission-name', ['class' => 'control-label']) ?>
            <?= Html::textInput('name', null, ['id' => 'permission-name', 'class' => 'form-control']) ?>
        </div>

        <div class="form-group">
            <?= Html::label('Descripción', 'permission-description', ['class' => 'control-label']) ?>
            <?= Html::textArea('description', null, ['id' => 'permission-description', 'class' => 'form-control', 'rows' => 3]) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div> 