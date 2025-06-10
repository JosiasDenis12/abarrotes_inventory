<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\User;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Crear roles si no existen
        $roles = ['admin', 'manager', 'staff'];
        foreach ($roles as $roleName) {
            if (!$auth->getRole($roleName)) {
                $role = $auth->createRole($roleName);
                $auth->add($role);
                echo "Rol {$roleName} creado exitosamente.\n";
            } else {
                echo "El rol {$roleName} ya existe.\n";
            }
        }

        // Asignar rol admin al usuario admin si existe
        $user = User::findOne(['username' => 'admin']);
        if ($user) {
            $adminRole = $auth->getRole('admin');
            if (!$auth->getAssignment('admin', $user->id)) {
                $auth->assign($adminRole, $user->id);
                echo "Rol admin asignado al usuario admin\n";
            } else {
                echo "El usuario admin ya tiene asignado el rol admin\n";
            }
        } else {
            echo "No se encontró el usuario admin\n";
        }

        return ExitCode::OK;
    }
} 