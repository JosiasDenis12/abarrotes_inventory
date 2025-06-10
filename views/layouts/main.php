<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - <?= Yii::$app->name ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/img/logo.png', ['alt'=>Yii::$app->name, 'height'=>'40']) . ' ' . Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-primary fixed-top',
        ],
    ]);
    
    $menuItems = [];
    
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Iniciar Sesión', 'url' => ['/site/login']];
        $menuItems[] = ['label' => 'Modo Invitado', 'url' => ['/site/guest']];
    } else {
        $menuItems[] = ['label' => 'Dashboard', 'url' => ['/site/dashboard']];
        $menuItems[] = ['label' => 'Productos', 'url' => ['/product/index']];
        $menuItems[] = ['label' => 'Ventas', 'url' => ['/sale/index']];
        $menuItems[] = ['label' => 'Proveedores', 'url' => ['/supplier/index']];
        $menuItems[] = ['label' => 'Reportes', 'url' => ['/sale/report']];
        
        $user = Yii::$app->user->identity;
        $profileImage = $user->profile_image ? Yii::getAlias('@web/uploads/' . $user->profile_image) : Yii::getAlias('@web/img/default-user.png');
        
        $menuItems[] = '<li class="nav-item dropdown">'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
            . '<a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">'
            . Html::img($profileImage, ['alt' => 'Foto de perfil', 'class' => 'rounded-circle', 'style' => 'width:32px; height:32px; object-fit:cover; margin-right:8px;'])
            . Html::encode($user->username)
            . ' <span class="text-muted">(' . ucfirst($user->role) . ')</span>'
            . '</a>'
            . '<div class="dropdown-menu dropdown-menu-right">'
            . '<a class="dropdown-item" href="' . Url::to(['/user/profile']) . '">Perfil</a>'
            . '<div class="dropdown-divider"></div>'
            . Html::submitButton(
                'Cerrar Sesión (' . Yii::$app->user->identity->username . ')',
                ['class' => 'dropdown-item', 'data-method' => 'post']
            )
            . Html::endForm()
            . '</div>'
            . '</li>';
    }
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ml-auto'],
        'items' => $menuItems,
    ]);
    
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-center">
    <div class="container">
        <p class="text-muted">&copy; Abarrotes Tendejosn San Francisco <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>