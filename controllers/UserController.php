<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['profile'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionProfile()
    {
        $userId = Yii::$app->user->id;
        $model = \app\models\User::findOne($userId); // <-- Carga el modelo real

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'profile_image');
            if ($image) {
                $imageName = 'user_' . $model->id . '_' . time() . '.' . $image->extension;
                $savePath = Yii::getAlias('@webroot/uploads/') . $imageName;
                if ($image->saveAs($savePath)) {
                    $model->profile_image = $imageName;
                }
            }
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Perfil actualizado.');
                return $this->redirect(['profile']);
            }
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }
}