<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Meal $model */
/** @var yii\widgets\ActiveForm $form */

$emoji = ['🍕', '🍔', '🍎', '🥑', '🥗', '🍣', '🍩', '🌮', '🍉', '🍞', '🍜', '🥩', '🍪', '🥕', '🧀', '🍓', '🍍', '🥒', '🍇', '🥞', '🦞', '🍗', '🍛'];
$randEmojiIndex = array_rand($emoji, 1);
$this->registerJS("
    let foodEmojis = ".json_encode($emoji).";
    let index = 0;
    let lastIndex = ".$randEmojiIndex.";

    function cycleEmojis() {
        let newIndex;
        do {
            newIndex = Math.floor(Math.random() * foodEmojis.length);
        } while (newIndex === lastIndex); // Ensure it's different from the last one

        lastIndex = newIndex; // Update lastIndex to track the last used emoji

        $('#upload-title').fadeOut(200, function() {
            $(this).html('Upload Your ' + foodEmojis[newIndex]).fadeIn(200);
        });
    }

    setInterval(cycleEmojis, 1500); // Change every 1.5 seconds
    
     $('#mealform-picture').on('change', function(ev) {
             $('#submitButton').text('Processing...');
             $('#submitButton').attr('disabled', true);
             $(this).parents('form').submit();
             ev.preventDefault();
    });
");
?>

<div class="meal-form container mt-5">
    <div class="card shadow-sm p-4">
        <h5 id="upload-title" class="mb-3">Upload Your <?= $emoji[$randEmojiIndex] ?></h5>

        <?php
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="mb-3">
            <label for="mealform-picture" class="form-label">Your picture will automatically submit after selected. Just
                be patient.</label>
            <?= $form->field($model, 'picture')
                ->fileInput([
                    'class' => 'form-control',
                    //'capture' => 'environment',
                ]); ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['id' => 'submitButton', 'class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>