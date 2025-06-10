<?php


require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';

$app = new yii\web\Application($config);

$user = \app\models\User::findOne(['username' => 'admin']);
$user->setPassword('123456'); // Cambia '123456' por la contraseña que quieras
$user->save();

echo "Contraseña cambiada exitosamente.\n";