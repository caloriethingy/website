<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Meal $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="meal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'file_name')->hiddenInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'calories')->textInput() ?>

    <?= $form->field($model, 'protein')->textInput() ?>

    <?= $form->field($model, 'fat')->textInput() ?>

    <?= $form->field($model, 'carbohydrates')->textInput() ?>

    <?= $form->field($model, 'fiber')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
