<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var array $salesAgents */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php
    $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusName(true)) ?>

    <?= $form->field($model, 'role')->dropDownList(
        ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'),
        ['prompt' => '-- Select role --']
    ) ?>

    <?= $form->field($model, 'sales_agent_id')->label(Yii::t('app', 'Sales Agent'))->dropDownList(
        $salesAgents,
        ['prompt' => '-- Select Sales Agent --']
    ) ?>

    <br/>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php
    ActiveForm::end(); ?>

</div>
