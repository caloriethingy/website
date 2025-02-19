<?php

/** @var \yii\web\View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php
$this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php
        $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body class="d-flex flex-column h-100">
    <?php
    $this->beginBody() ?>

    <header>
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-xxl navbar-dark bg-dark fixed-top navbar-fixed-50',
            ],
        ]);
        $menuItems = [];

        if (!Yii::$app->user->isGuest) {
            $menuItems[] = [
                'label' => 'Capture Meal',
                'url' => [Url::to(['meal/upload'])],
            ];
            $menuItems[] = [
                'label' => 'List Meals',
                'url' => [Url::to(['meal/index'])],
            ];
            $menuItems[] = [
                'label' => 'Summary',
                'url' => [Url::to(['summary'])],
            ];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto'],
            'items' => $menuItems,
        ]);
        if (Yii::$app->user->isGuest) {
            echo
            Html::a(
                'Signup',
                ['/site/signup'],
                [
                    'class' => ['btn btn-link login text-decoration-none'],
                ]
            );
            echo
            Html::a(
                'Login',
                ['/site/login'],
                [
                    'class' => ['btn btn-link login text-decoration-none'],
                ]
            );
        } else {
            echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->email . ')',
                    ['class' => 'btn btn-link logout text-decoration-none']
                )
                . Html::endForm();
        }
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

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        </div>
    </footer>

    <?php
    $this->endBody() ?>
    </body>
    </html>
<?php
$this->endPage();
