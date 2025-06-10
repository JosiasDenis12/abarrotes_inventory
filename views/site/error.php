<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var \yii\web\ErrorAction $exception */

$this->title = $name;
?>
<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>
    <p>
        Ocurrió el siguiente error mientras el servidor procesaba su solicitud.
    </p>
    <p>
        Si cree que es un error del servidor, por favor contacte al administrador.
    </p>
</div>