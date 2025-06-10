<?php

namespace app\modules\admin;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function init()
    {
        parent::init();
        
        // Configuración personalizada del módulo
        // $this->layout = '@app/views/layouts/main.php';
    }
} 