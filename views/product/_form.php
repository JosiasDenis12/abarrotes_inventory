<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
/* @var $categories app\models\Category[] */
/* @var $suppliers app\models\Supplier[] */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'category_id')->dropDownList(
                ArrayHelper::map($categories, 'id', 'name'),
                ['prompt' => 'Seleccionar Categoría']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'supplier_id')->dropDownList(
                ArrayHelper::map($suppliers, 'id', 'name'),
                ['prompt' => 'Seleccionar Proveedor']
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'price')->textInput(['type' => 'number', 'step' => '0.01']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'cost_price')->textInput(['type' => 'number', 'step' => '0.01']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'stock')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'min_stock')->textInput(['type' => 'number']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'unit')->textInput(['maxlength' => true, 'placeholder' => 'Ej: Pieza, Kg, Lt']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'location')->textInput(['maxlength' => true, 'placeholder' => 'Ubicación en bodega']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'image')->fileInput(['class' => 'form-control-file']) ?>
            <?php if (!$model->isNewRecord && $model->image): ?>
                <div class="mt-2">
                    <p>Imagen actual:</p>
                    <?= Html::img(Yii::getAlias('@web/uploads/products/' . $model->image), ['class' => 'img-thumbnail', 'style' => 'max-height: 100px']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group mt-4">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
