<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Meal $model */
/** @var yii\widgets\ActiveForm $form */

$emoji = [
    '🍕',
    '🍔',
    '🍎',
    '🥑',
    '🥗',
    '🍣',
    '🍩',
    '🌮',
    '🍉',
    '🍞',
    '🍜',
    '🥩',
    '🍪',
    '🥕',
    '🧀',
    '🍓',
    '🍍',
    '🥒',
    '🍇',
    '🥞',
    '🦞',
    '🍗',
    '🍛'
];
$randEmojiIndex = array_rand($emoji, 1);
$this->registerJS(
    "
    let foodEmojis = " . json_encode($emoji) . ";
    let spinning = false;

    function startSlotMachine() {
        if (spinning) return;
        spinning = true;

        let slotSpeed = 50;
        let slowdownFactor = 1.1;
        let spinCount = 0;
        let maxSpins = Math.random();

        let interval = setInterval(() => {
            let randomIndex = Math.floor(Math.random() * foodEmojis.length);
            $('#upload-title').text('Upload Your ' + foodEmojis[randomIndex]);

            spinCount++;
            slotSpeed *= slowdownFactor;

            if (spinCount >= maxSpins) {
                clearInterval(interval);
                let finalIndex = Math.floor(Math.random() * foodEmojis.length);
                $('#upload-title').text('Upload Your ' + foodEmojis[finalIndex]);
                spinning = false;
            }
        }, slotSpeed);
    }

    $('#upload-title').click(startSlotMachine);

    $('#mealform-picture').on('change', function(ev) {
     if (localStorage.getItem('autoUpload') === 'true') { // Check localStorage
        $('#submitButton').text('Processing...');
        $('#submitButton').attr('disabled', true);
        $(this).parents('form').submit();
        ev.preventDefault();
      }
    });
"
);
?>

<div class="meal-form container mt-5">
    <div class="upload-box">
        <h5 id="upload-title" class="mb-3 fs-2 fw-bold">Upload Your <?= $emoji[$randEmojiIndex] ?></h5>

        <?php
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="meal-description" placeholder="Add context (optional)" autofocus tabindex="1">
            </div>
        </div>
        <div id="gesture-area" class="gesture-area">
            Tap to take a picture or Long Press to upload a file
            <img id="image-preview" src="#" alt="Image Preview" style="display: none; max-width: 100%;">
        </div>

        <?= Html::activeFileInput($model, 'picture', ['style' => 'display: none;', 'id' => 'file-input']) ?>
        <input type="file" id="camera-input" accept="image/*" capture="environment" style="display: none;">
        <input type="hidden" id="meal_day" value="Today">
        <div id="metadata-fields">
            <div class="mb-3">
                <div class="btn-group d-flex justify-content-center" role="group">
                            <button id="prev-day-btn" class="btn btn-light" type="button">&lt;</button>
                            <button id="current-day-btn" class="btn btn-light" type="button" disabled>Today</button>
                            <button id="next-day-btn" class="btn btn-light" type="button" disabled>&gt;</button>
                </div>
            </div>
            <div class="btn-group d-flex justify-content-center" role="group">
                <input type="radio" class="btn-check" name="meal_for" id="breakfast" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="breakfast">Breakfast</label>

                <input type="radio" class="btn-check" name="meal_for" id="lunch" autocomplete="off">
                <label class="btn btn-outline-primary" for="lunch">Lunch</label>

                <input type="radio" class="btn-check" name="meal_for" id="dinner" autocomplete="off">
                <label class="btn btn-outline-primary" for="dinner">Dinner</label>

                <input type="radio" class="btn-check" name="meal_for" id="other" autocomplete="off">
                <label class="btn btn-outline-primary" for="other">🤷</label>
            </div>
        </div>

        <div class="mb-3 form-check mt-3">
            <label class="form-check-label" for="auto-upload-checkbox">Automatically upload</label>
            <input type="checkbox" class="form-check-input" id="auto-upload-checkbox">
        </div>
        <div class="d-grid gap-2 d-md-block">
            <?= Html::submitButton('Save meal!', ['id' => 'submitButton', 'class' => 'btn btn-success']) ?>

        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <style>
        #upload-title {
            font-size: 2rem;
            font-weight: bold;
            min-height: 50px;
            display: inline-block;
            transition: color 0.2s ease-in-out;
        }

        #gesture-area {
            -webkit-user-select: none; /* Safari */
            -ms-user-select: none; /* IE 10+ and Edge */
            user-select: none; /* Standard syntax */
            -webkit-touch-callout: none; /* Prevents default callout on hold in iOS */
        }


        .gesture-area {
            border: 2px dashed #ccc;
            padding: 20px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
    <script>
        const gestureArea = document.getElementById('gesture-area');
        const fileInput = document.getElementById('file-input');
        const cameraInput = document.getElementById('camera-input');
        const imagePreview = document.getElementById('image-preview'); // Get the image element

        const body = document.body;

        const prevDayBtn = document.getElementById('prev-day-btn');
        const nextDayBtn = document.getElementById('next-day-btn');
        const currentDayBtn = document.getElementById('current-day-btn'); // Changed to button

        const autoUploadCheckbox = document.getElementById('auto-upload-checkbox');



        fileInput.addEventListener('change', (event) => {
            const file = event.target.files;

            if (file) {
                const reader = new FileReader();

                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }

                reader.readAsDataURL(file);
            }
        });
        // Load checkbox state from localStorage
        if (localStorage.getItem('autoUpload') === 'true') {
            autoUploadCheckbox.checked = true;
        }

        // Save checkbox state to localStorage on change
        autoUploadCheckbox.addEventListener('change', () => {
            localStorage.setItem('autoUpload', autoUploadCheckbox.checked);
        });

        let currentDate = new Date();
        let today = new Date(); // Store today's date
        function updateDayDisplay() {
            const diff = Math.floor((today - currentDate) / (1000 * 60 * 60 * 24)); // Difference in days
            const options = {weekday: 'long'};

            if (diff === 0) {
                currentDayBtn.textContent = 'Today';
            } else if (diff === 1) {
                currentDayBtn.textContent = 'Yesterday';
            } else {
                currentDayBtn.textContent = currentDate.toLocaleDateString(undefined, options);
            }
            document.getElementById('meal_day').value = currentDayBtn.textContent;

            // Disable "next" button if currentDate is today
            nextDayBtn.disabled = (currentDate.toDateString() === today.toDateString());

            // Disable "prev" button if currentDate is 4 days before today
            prevDayBtn.disabled = (diff >= 4);
        }

        updateDayDisplay();

        prevDayBtn.addEventListener('click', () => {
            currentDate.setDate(currentDate.getDate() - 1);
            updateDayDisplay();
        });

        nextDayBtn.addEventListener('click', () => {
            currentDate.setDate(currentDate.getDate() + 1);
            updateDayDisplay();
        });

        function isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        if (isMobileDevice()) {
            gestureArea.textContent = 'Click to take a picture or long press to add from the media gallery';
            // Mobile device: Handle long-press and tap
            let longPressTimer;
            let touchStartX = 0;
            let touchStartY = 0;
            let isLongPress = false;

            gestureArea.addEventListener('touchstart', (e) => {
                touchStartX = e.touches.clientX;
                touchStartY = e.touches.clientY;

                longPressTimer = setTimeout(() => {
                    isLongPress = true;
                    body.style.backgroundColor = '';
                    fileInput.click();
                }, 1000); // Adjust duration as needed

                body.style.backgroundColor = 'rgba(0, 0, 0, 0.2)';
            });

            gestureArea.addEventListener('touchmove', (e) => {
                clearTimeout(longPressTimer);
                isLongPress = false;
                body.style.backgroundColor = '';
            });

            gestureArea.addEventListener('touchend', () => {
                clearTimeout(longPressTimer);
                body.style.backgroundColor = '';
                if (!isLongPress) {
                    cameraInput.click();
                }
                isLongPress = false;
            });

        } else {
            // Non-mobile device: Handle click for file upload
            gestureArea.textContent = 'Click to upload your meal';
            gestureArea.addEventListener('click', () => {
                fileInput.click();
            });
        }

        /**
         * Setup default buttons
         */
        const breakfastBtn = document.getElementById('breakfast');
        const lunchBtn = document.getElementById('lunch');
        const dinnerBtn = document.getElementById('dinner');
        const otherBtn = document.getElementById('other');

        // Function to pre-select meal type based on time of day
        function preselectMealType() {
            const now = new Date();
            const hour = now.getHours();
            if (hour >= 6 && hour < 11) { // Breakfast time
                breakfastBtn.click();
            } else if (hour >= 11 && hour < 15) { // Lunch time
                lunchBtn.click();
            } else if (hour >= 15 && hour < 21) { // Dinner time
                dinnerBtn.click();
            } else {
                otherBtn.click();
            }
        }
        // Call the function to pre-select on page load
        preselectMealType();
    </script>
</div>