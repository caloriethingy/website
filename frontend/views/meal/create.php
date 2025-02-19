<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Meal $model */

$this->title = 'Create Meal';
$this->params['breadcrumbs'][] = ['label' => 'Meals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
