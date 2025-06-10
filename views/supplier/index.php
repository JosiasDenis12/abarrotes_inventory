<?php

use yii\helpers\Html;
use yii\grid\GridView;
use Yii;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gestión de Proveedores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
            <?= Html::a('<i class="fas fa-plus"></i> Crear Proveedor', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'name',
                        'contact_person',
                        'email:email',
                        'phone',
                        [
                            'attribute' => 'address',
                            'format' => 'ntext',
                            'contentOptions' => ['style' => 'max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-eye"></i>', $url, [
                                        'title' => 'Ver',
                                        'class' => 'btn btn-sm btn-info',
                                    ]);
                                },
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="fas fa-edit"></i>', $url, [
                                        'title' => 'Editar',
                                        'class' => 'btn btn-sm btn-primary',
                                    ]);
                                },
                                'delete' => function ($url, $model) {
                                    if (Yii::$app->user->can('delete_supplier')) {
                                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                                            'title' => 'Eliminar',
                                            'class' => 'btn btn-sm btn-danger',
                                            'data' => [
                                                'confirm' => '¿Estás seguro de eliminar este proveedor?',
                                                'method' => 'post',
                                            ],
                                        ]);
                                    }
                                    return '';
                                },
                            ],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::a('<i class="fas fa-file-export"></i> Exportar a CSV', ['export'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

</div>
