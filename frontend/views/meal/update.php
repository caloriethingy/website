<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Meal $model */

$this->title = 'Update Meal: ' . $model->food_name;
$this->params['breadcrumbs'][] = ['label' => 'Meals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->food_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="meal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
