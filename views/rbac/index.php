<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */

$this->title = 'Gestión de Roles y Permisos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rbac-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Rol', ['create-role'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Crear Permiso', ['create-permission'], ['class' => 'btn btn-primary']) ?>
    </p>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Roles</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roles as $role): ?>
                            <tr>
                                <td><?= Html::encode($role->name) ?></td>
                                <td><?= Html::encode($role->description) ?></td>
                                <td>
                                    <?= Html::a('Permisos', ['assign-permissions', 'roleName' => $role->name], ['class' => 'btn btn-info btn-xs']) ?>
                                    <?= Html::a('Eliminar', ['delete-role', 'roleName' => $role->name], [
                                        'class' => 'btn btn-danger btn-xs',
                                        'data' => [
                                            'confirm' => '¿Está seguro de que desea eliminar este rol?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Permisos</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($permissions as $permission): ?>
                            <tr>
                                <td><?= Html::encode($permission->name) ?></td>
                                <td><?= Html::encode($permission->description) ?></td>
                                <td>
                                    <?= Html::a('Eliminar', ['delete-permission', 'permissionName' => $permission->name], [
                                        'class' => 'btn btn-danger btn-xs',
                                        'data' => [
                                            'confirm' => '¿Está seguro de que desea eliminar este permiso?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 