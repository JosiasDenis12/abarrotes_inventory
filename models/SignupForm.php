<?php

namespace app\models;

use yii\base\Model;
use app\models\User;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $role;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'role'], 'required'],
            ['email', 'email'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Este usuario ya existe.'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Este correo ya está registrado.'],
            ['password', 'string', 'min' => 6],
            ['role', 'in', 'range' => ['staff', 'manager', 'guest']], // agrega los roles permitidos
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = 10;
        $user->role = $this->role;
        $user->created_at = time();
        $user->updated_at = time();
        if (!$user->save()) {
            var_dump($user->getErrors());
            die();
        }
        return $user;
    }
}