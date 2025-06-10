<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yii\rbac\Permission */

$this->title = 'Crear Permiso';
$this->params['breadcrumbs'][] = ['label' => 'Gestión de Roles y Permisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-create-permission">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-permission', [
        'model' => $model,
    ]) ?>

</div> 