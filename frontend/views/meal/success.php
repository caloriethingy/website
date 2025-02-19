<?php

use common\models\Meal;
use frontend\assets\ConfettiAsset;

/* @var $this yii\web\View */

$this->title = Yii::$app->name;

ConfettiAsset::register($this);


$this->registerJs("
var end = Date.now() + (2 * 1000);
var scalar = 2;
var foodEmojis = ['🍕', '🍔', '🍎', '🥑', '🥗', '🍣', '🍩', '🌮', '🍉', '🍞', '🍜', '🥩', '🍪', '🥕', '🧀', '🍓', '🍍', '🥒', '🍇', '🥞', '🦞', '🍗', '🍛'];
// Create shapes from all emojis
var emojiShapes = foodEmojis.map(emoji => confetti.shapeFromText({ text: emoji, scalar }));
var colors = ['#4a8fc4', '#FFA500', '#585858'];
// Function to pick 3 random unique emojis
function getRandomEmojis() {
  let shuffled = emojiShapes.sort(() => 0.5 - Math.random());
  return shuffled.slice(0, 3); // Get first 3 elements
}

// Detect if user is on a phone (simple check)
var isMobile = window.innerWidth < 768; // Adjust as needed for tablets

(function frame() {
  confetti({    
    particleCount: isMobile ? 1 : 2, // Fewer particles on mobile
    angle: 60,
    spread: isMobile ? 35 : 55, // Less spread on mobile,
    origin: { x: 0 },
    shapes: getRandomEmojis(),
    scalar: isMobile ? 1.5 : 2 // Slightly smaller on mobile
  });
  confetti({
    particleCount: isMobile ? 1 : 2, // Fewer particles on mobile
    angle: 120,
    spread: isMobile ? 35 : 55, // Less spread on mobile,
    origin: { x: 1 },
    shapes: getRandomEmojis(),
    scalar: isMobile ? 1.5 : 2 // Slightly smaller on mobile
  });

  if (Date.now() < end) {
      requestAnimationFrame(frame);
  }
}());");
?>
<div class="analysis-result text-center p-4">
    <h2 class="mb-3">Yum! 🎉</h2>

    <p class="lead">
        Our AI minions have <i>finished judging</i> you...
        and your food. But also you. 🤖🍕
    </p>

    <div class="card mx-auto p-3" style="max-width: 400px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); z-index: 200;">
        <h4 class="mb-3 text-center">🍽️ <strong><?= $model->food_name ?></strong></h4>
        <div class="row">
            <div class="col-6 text-right">
                <p class="mb-0"><strong>🔥 Calories</strong></p>
                <p class="mb-0"><strong>🍗 Protein</strong></p>
                <p class="mb-0"><strong>🥑 Fat</strong></p>
                <p class="mb-0"><strong>🍞 Carbs</strong></p>
            </div>

            <div class="col-6">
                <p class="mb-0"><?= $model->calories ?> kcal</p>
                <p class="mb-0"><?= $model->protein ?> g</p>
                <p class="mb-0"><?= $model->fat ?> g</p>
                <p class="mb-0"><?= $model->carbohydrates ?> g</p>
            </div>
        </div>
    </div>
    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <p><a class="btn btn-lg btn-primary" href="/meal/upload" style="z-index: 200;">Feed me more data! 🍔</a></p>
        </div>
    </div>
</div>