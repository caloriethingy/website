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
        <?php
        if (!Yii::$app->user->isGuest) {
        ?>
        <script type="text/javascript">window.$sleek = [];
            window.SLEEK_PRODUCT_ID = 87924416;
            (function () {
                d = document;
                s = d.createElement("script");
                s.src = "https://client.sleekplan.com/sdk/e.js";
                s.async = 1;
                d.getElementsByTagName("head")[0].appendChild(s);
            })();</script>
        <?php } ?>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php
        $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php
        $this->head() ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                'label' => 'Summary',
                'url' => [Url::to(['meal/index'])],
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
            <p class="float-start">
                &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>
                <a href="<?= Url::to(['/site/terms']) ?>" class="text-black">Terms and Conditions</a>
                <a href="<?= Url::to(['/site/privacy']) ?>" class="text-black">Privacy Policy</a>&nbsp;
                <a href="https://x.com/caloriethingy" target="_blank" class="text-black mx-2"><i class="bi bi-twitter-x"></i></a>
                <a href="https://github.com/caloriethingy/website" target="_blank" class="text-black">
                    <i class="bi bi-github"></i>
                </a>
            </p>
        </div>
    </footer>

    <?php
    $this->endBody() ?>
    </body>
    </html>
<?php
$this->endPage();
