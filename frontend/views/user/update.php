<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var array $salesAgents */

$this->title = Yii::t('app', 'Update User: {name}', [
    'name' => $model->email,
]);
?>
<div class="user-update container1">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'salesAgents' => $salesAgents
    ]) ?>

</div>
