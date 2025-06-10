<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $roleName string */
/* @var $permissions yii\rbac\Permission[] */
/* @var $assignedPermissions yii\rbac\Permission[] */

$this->title = 'Asignar Permisos a Rol: ' . $roleName;
$this->params['breadcrumbs'][] = ['label' => 'Gestión de Roles y Permisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-assign-permissions">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="box box-primary">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="form-group">
                <?= Html::label('Permisos Disponibles', 'permissions', ['class' => 'control-label']) ?>
                <?= Html::checkboxList('permissions', 
                    array_map(function($p) { return $p->name; }, $assignedPermissions),
                    array_reduce($permissions, function($result, $p) {
                        $result[$p->name] = $p->description ?: $p->name;
                        return $result;
                    }, []),
                    ['class' => 'form-control', 'multiple' => true]
                ) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div> 