<?php

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

    <div class="container text-center mt-4">
        <div class="row justify-content-center">
            <!-- Food Item Card -->
            <div class="col-md-6 d-flex">
                <div class="card p-3 w-100 h-100 d-flex flex-column" style="max-width: 400px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); z-index: 200;">
                    <h4 class="mb-3 text-center">🍽️ <strong><?= $model->food_name ?></strong></h4>
                    <div class="row flex-grow-1">
                        <div class="col-6 text-end">
                            <p class="mb-0"><strong>🔥 Calories</strong></p>
                            <p class="mb-0"><strong>🍗 Protein</strong></p>
                            <p class="mb-0"><strong>🥑 Fat</strong></p>
                            <p class="mb-0"><strong>🍞 Carbs</strong></p>
                            <p class="mb-0"><strong>🌾 Fiber</strong></p>
                        </div>
                        <div class="col-6 text-start">
                            <p class="mb-0"><?= $model->calories ?> kcal</p>
                            <p class="mb-0"><?= $model->protein ?> g</p>
                            <p class="mb-0"><?= $model->fat ?> g</p>
                            <p class="mb-0"><?= $model->carbohydrates ?> g</p>
                            <p class="mb-0"><?= $model->fiber ?> g</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Totals Section -->
            <div class="col-md-6 d-flex mt-3 mt-md-0">
                <div class="card p-3 w-100 h-100 d-flex flex-column" style="max-width: 400px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <h4 class="card-title text-center">📊 <strong>Today So Far</strong></h4>
                    <div class="row flex-grow-1">
                        <div class="col-6 text-end">
                            <p class="mb-0"><strong>🔥 Calories</strong></p>
                            <p class="mb-0"><strong>🍗 Protein</strong></p>
                            <p class="mb-0"><strong>🥑 Fat</strong></p>
                            <p class="mb-0"><strong>🍞 Carbs</strong></p>
                            <p class="mb-0"><strong>🌾 Fiber</strong></p>
                        </div>
                        <div class="col-6 text-start">
                            <p class="mb-0"><?= $today['calories']; ?> kcal</p>
                            <p class="mb-0"><?= $today['protein']; ?> g</p>
                            <p class="mb-0"><?= $today['fat']; ?> g</p>
                            <p class="mb-0"><?= $today['carbohydrates']; ?> g</p>
                            <p class="mb-0"><?= $today['fiber']; ?> g</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <p><a class="btn btn-lg btn-primary" href="/meal/upload" style="z-index: 200;">Feed me more data! 🍔</a></p>
        </div>
    </div>
</div>