<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rbac\Role;
use yii\rbac\Permission;
use app\models\User;

class RbacController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lista todos los roles y permisos
     * @return mixed
     */
    public function actionIndex()
    {
        $roles = Yii::$app->authManager->getRoles();
        $permissions = Yii::$app->authManager->getPermissions();

        return $this->render('index', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Crea un nuevo rol
     * @return mixed
     */
    public function actionCreateRole()
    {
        $role = new Role();
        
        if ($role->load(Yii::$app->request->post()) && $role->validate()) {
            Yii::$app->authManager->add($role);
            Yii::$app->session->setFlash('success', 'Rol creado exitosamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create-role', [
            'role' => $role,
        ]);
    }

    /**
     * Crea un nuevo permiso
     * @return mixed
     */
    public function actionCreatePermission()
    {
        $permission = new Permission();
        
        if ($permission->load(Yii::$app->request->post()) && $permission->validate()) {
            Yii::$app->authManager->add($permission);
            Yii::$app->session->setFlash('success', 'Permiso creado exitosamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create-permission', [
            'permission' => $permission,
        ]);
    }

    /**
     * Asigna permisos a un rol
     * @param string $roleName
     * @return mixed
     */
    public function actionAssignPermissions($roleName)
    {
        $role = Yii::$app->authManager->getRole($roleName);
        if (!$role) {
            throw new NotFoundHttpException('El rol no existe.');
        }

        $permissions = Yii::$app->authManager->getPermissions();
        $rolePermissions = Yii::$app->authManager->getPermissionsByRole($roleName);

        if (Yii::$app->request->isPost) {
            $selectedPermissions = Yii::$app->request->post('permissions', []);
            
            // Revocar todos los permisos actuales
            foreach ($rolePermissions as $permission) {
                Yii::$app->authManager->removeChild($role, $permission);
            }

            // Asignar los nuevos permisos
            foreach ($selectedPermissions as $permissionName) {
                $permission = Yii::$app->authManager->getPermission($permissionName);
                if ($permission) {
                    Yii::$app->authManager->addChild($role, $permission);
                }
            }

            Yii::$app->session->setFlash('success', 'Permisos actualizados exitosamente.');
            return $this->redirect(['index']);
        }

        return $this->render('assign-permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    /**
     * Asigna roles a un usuario
     * @param integer $userId
     * @return mixed
     */
    public function actionAssignRoles($userId)
    {
        $user = User::findOne($userId);
        if (!$user) {
            throw new NotFoundHttpException('El usuario no existe.');
        }

        $roles = Yii::$app->authManager->getRoles();
        $userRoles = $user->getRoles();

        if (Yii::$app->request->isPost) {
            $selectedRoles = Yii::$app->request->post('roles', []);
            
            // Revocar todos los roles actuales
            $user->revokeAllRoles();

            // Asignar los nuevos roles
            foreach ($selectedRoles as $roleName) {
                $user->assignRole($roleName);
            }

            Yii::$app->session->setFlash('success', 'Roles actualizados exitosamente.');
            return $this->redirect(['user/view', 'id' => $userId]);
        }

        return $this->render('assign-roles', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
        ]);
    }

    /**
     * Elimina un rol
     * @param string $roleName
     * @return mixed
     */
    public function actionDeleteRole($roleName)
    {
        $role = Yii::$app->authManager->getRole($roleName);
        if ($role) {
            Yii::$app->authManager->remove($role);
            Yii::$app->session->setFlash('success', 'Rol eliminado exitosamente.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Elimina un permiso
     * @param string $permissionName
     * @return mixed
     */
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