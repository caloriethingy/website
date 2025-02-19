<?php

/** @var yii\web\View $this */

$this->title = 'Calorie Thingy';

?>

<div class="alert alert-info" role="info">
    👋 <i>Please note:  This app provides estimated nutritional values.  It is not a substitute for professional dietary advice.</i>
</div>
<div class="site-index">
    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4">Calorie Ease</h1>
            <p class="fs-5 fw-light">Track your food with a picture!</p>
            <p>
                <a class="btn btn-lg btn-success" href="<?= Yii::$app->getUrlManager()->createUrl(['meal/upload']) ?>">Capture a meal</a>
                <a class="btn btn-lg btn-primary" href="<?= Yii::$app->getUrlManager()->createUrl(['meal/index']) ?>">View Summary</a></p>
        </div>
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <h2><i class="bi bi-camera-video"></i>  Upload Food Photos</h2>

                <p>Easily upload photos of your meals and snacks. Our AI analyzes the images to provide estimated nutrition data.</p>
            </div>
            <div class="col-lg-4">
                <h2><i class="bi bi-bar-chart-line"></i>  Daily Nutrition Summary</h2>

                <p>View a concise summary of your daily calorie, protein, fat, carbohydrate, and fiber intake.  This helps track your progress and goals.</p>
            </div>
            <div class="col-lg-4">
                <h2><i class="bi bi-calendar-event"></i>  Meal Logging & Tracking</h2>

                <p>Effortlessly log your meals and snacks throughout the day. The app will automatically calculate your daily totals.</p>
            </div>
        </div>
        <p></p>


    </div>
</div>
