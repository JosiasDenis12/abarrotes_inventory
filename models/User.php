<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $role
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_STAFF = 'staff';
    const ROLE_GUEST = 'guest';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'auth_key', 'password_hash', 'status', 'role', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['role'], 'string', 'max' => 20],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['profile_image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /** 
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Checks if password reset token is valid.
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = 3600; // 1 hora (puedes cambiar el tiempo de expiración)
        return $timestamp + $expire >= time();
    }

    /**
     * Obtiene los roles asignados al usuario
     * @return array
     */
    public function getRoles()
    {
        return Yii::$app->authManager->getRolesByUser($this->id);
    }

    /**
     * Obtiene los permisos asignados al usuario
     * @return array
     */
    public function getPermissions()
    {
        return Yii::$app->authManager->getPermissionsByUser($this->id);
    }

    /**
     * Verifica si el usuario tiene un rol específico
     * @param string $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        $roles = $this->getRoles();
        return isset($roles[$roleName]);
    }

    /**
     * Verifica si el usuario tiene un permiso específico
     * @param string $permissionName
     * @param array $params
     * @return bool
     */
    public function hasPermission($permissionName, $params = [])
    {
        return Yii::$app->authManager->checkAccess($this->id, $permissionName, $params);
    }

    /**
     * Asigna un rol al usuario
     * @param string $roleName
     * @return bool
     */
    public function assignRole($roleName)
    {
        $role = Yii::$app->authManager->getRole($roleName);
        if ($role) {
            Yii::$app->authManager->assign($role, $this->id);
            return true;
        }
        return false;
    }

    /**
     * Revoca un rol del usuario
     * @param string $roleName
     * @return bool
     */
    public function revokeRole($roleName)
    {
        $role = Yii::$app->authManager->getRole($roleName);
        if ($role) {
            Yii::$app->authManager->revoke($role, $this->id);
            return true;
        }
        return false;
    }

    /**
     * Revoca todos los roles del usuario
     * @return bool
     */
    public function revokeAllRoles()
    {
        Yii::$app->authManager->revokeAll($this->id);
        return true;
    }
}
