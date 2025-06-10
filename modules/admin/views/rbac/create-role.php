<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yii\rbac\Role */

$this->title = 'Crear Rol';
$this->params['breadcrumbs'][] = ['label' => 'Gestión de Roles y Permisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-create-role">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-role', [
        'model' => $model,
    ]) ?>

</div> 