<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class RbacController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete-role' => ['post'],
                    'delete-permission' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $roles = Yii::$app->authManager->getRoles();
        $permissions = Yii::$app->authManager->getPermissions();

        return $this->render('index', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function actionCreateRole()
    {
        if (Yii::$app->request->isPost) {
            $name = Yii::$app->request->post('name');
            $description = Yii::$app->request->post('description');

            $role = Yii::$app->authManager->createRole($name);
            $role->description = $description;

            if (Yii::$app->authManager->add($role)) {
                Yii::$app->session->setFlash('success', 'Rol creado exitosamente.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create-role');
    }

    public function actionCreatePermission()
    {
        if (Yii::$app->request->isPost) {
            $name = Yii::$app->request->post('name');
            $description = Yii::$app->request->post('description');

            $permission = Yii::$app->authManager->createPermission($name);
            $permission->description = $description;

            if (Yii::$app->authManager->add($permission)) {
                Yii::$app->session->setFlash('success', 'Permiso creado exitosamente.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create-permission');
    }

    public function actionAssignPermissions($roleName)
    {
        $role = Yii::$app->authManager->getRole($roleName);
        if (!$role) {
            throw new NotFoundHttpException('El rol no existe.');
        }

        if (Yii::$app->request->isPost) {
            $permissions = Yii::$app->request->post('permissions', []);
            
            // Revocar todos los permisos actuales
            Yii::$app->authManager->removeChildren($role);

            // Asignar los nuevos permisos
            foreach ($permissions as $permissionName) {
                $permission = Yii::$app->authManager->getPermission($permissionName);
                if ($permission) {
                    Yii::$app->authManager->addChild($role, $permission);
                }
            }

            Yii::$app->session->setFlash('success', 'Permisos asignados exitosamente.');
            return $this->redirect(['index']);
        }

        $permissions = Yii::$app->authManager->getPermissions();
        $assignedPermissions = Yii::$app->authManager->getPermissionsByRole($roleName);

        return $this->render('assign-permissions', [
            'roleName' => $roleName,
            'permissions' => $permissions,
            'assignedPermissions' => $assignedPermissions,
        ]);
    }

    public function actionDeleteRole($roleName)
    {
        $role = Yii::$app->authManager->getRole($roleName);
        if ($role) {
            Yii::$app->authManager->remove($role);
            Yii::$app->session->setFlash('success', 'Rol eliminado exitosamente.');
        }
        return $this->redirect(['index']);
    }

    public function actionDeletePermission($permissionName)
    {
        $permission = Yii::$app->authManager->getPermission($permissionName);
        if ($permission) {
            Yii::$app->authManager->remove($permission);
            Yii::$app->session->setFlash('success', 'Permiso eliminado exitosamente.');
        }
        return $this->redirect(['index']);
    }
} 