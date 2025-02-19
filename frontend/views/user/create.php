<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var array $salesAgents */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>A new password will be emailed to the user upon creation. The user will not need to validate their email address.</p>
    <p>If you want them to validate their email, have them sign up on the home page.</p>
    <?= $this->render('_form', [
        'model' => $model,
        'salesAgents' => $salesAgents,
    ]) ?>

</div>
