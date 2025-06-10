<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php'; // o console.php si lo tienes

$application = new yii\web\Application($config);

// Cambia 'josias' por el usuario que quieres actualizar
$user = \app\models\User::findOne(['username' => 'josias']);
if ($user) {
    $user->setPassword('    '); // Nueva contraseña
    $user->generateAuthKey(); // Opcional: actualiza el auth_key
    $user->save(false); // Guarda sin validar reglas (o usa $user->save() si quieres validar)
    echo "Contraseña actualizada correctamente.\n";
} else {
    echo "Usuario no encontrado.\n";
}