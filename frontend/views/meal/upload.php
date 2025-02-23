<?php

use common\models\Meal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\MealForm $model */
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
    let emojiIndex = " . $randEmojiIndex . ";
    setInterval(() => {
        $('#upload-title').text('Upload Your ' + foodEmojis[emojiIndex]);
        emojiIndex = (emojiIndex + 1) % foodEmojis.length; // Cycle through emojis
    }, 1100); // Adjust the interval (in milliseconds) for the desired speed

    $('#file-input').on('change', function(ev) {
     if (localStorage.getItem('autoUpload') === 'true') { // Check localStorage
        $('#submitButton').text('Processing...');
        $('#submitButton').attr('disabled', true);
        $(this).parents('form').submit();
        ev.preventDefault();
      }
    });
    
    $('#submitButton').on('click', function(ev) {
        $('#submitButton').text('Processing...');
        $('#submitButton').attr('disabled', true);
        $(this).parents('form').submit();
        ev.preventDefault();
      }
    );
"
);

$this->registerCssFile('@web/css/upload.css');
?>

<div class="meal-form container mt-5">
    <div class="upload-box">
        <h5 id="upload-title" class="mb-3 fs-2 fw-bold">Upload Your <?= $emoji[$randEmojiIndex] ?></h5>

        <?php
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->errorSummary($model); ?>
        <?=$form->field($model, 'context')->textInput([
            'class' => 'form-control mb-3',
            'placeholder' => 'Add context (optional)',
            'autofocus',
        ])->label(false) ?>

        <div id="gesture-area" class="gesture-area">
            Tap to take a picture or Long Press to upload a file
            <img id="image-preview" src="#" alt="Image Preview" style="display: none; max-width: 100%;">
        </div>

        <?= Html::activeFileInput($model, 'picture', ['style' => 'display: none;', 'id' => 'file-input']) ?>
        <?= $form->field($model, 'day')->hiddenInput()->label(false); ?>

        <div id="metadata-fields">
            <div class="mb-3">
                <div class="btn-group d-flex justify-content-center" role="group">
                    <button id="prev-day-btn" class="btn btn-light" type="button">&lt;</button>
                    <button id="current-day-btn" class="btn btn-light" type="button">Today</button>
                    <button id="next-day-btn" class="btn btn-light" type="button" disabled>&gt;</button>
                </div>
            </div>

                <?= $form
                    ->field($model, 'type')
                    ->radioList($model->getTypeList(), [
                            'class' => 'btn-group d-flex justify-content-center',
                            'item' => function ($index, $label, $name, $checked, $value) {
                                $return = '<input class="btn-check" type="radio" value="'.$value.'" id="'.$value.'" name="' . $name . '" autocomplete="off" ' . ($checked ? "checked" : "") . '>';
                                $return .= '<label class="btn btn-outline-primary" for="'.$value.'">' . $label . '</label>';
                                return $return;
                            },
                    ])
                    ->label(false); ?>
        </div>

        <div class="mb-3 form-check mt-3">
            <label class="form-check-label" for="auto-upload-checkbox">Automatically upload</label>
            <input type="checkbox" class="form-check-input" id="auto-upload-checkbox">
        </div>
        <div class="d-grid gap-2 d-md-block">
            <?= Html::submitButton('Save meal!', ['id' => 'submitButton', 'class' => 'btn btn-success']) ?>
        </div>

        <?php
        ActiveForm::end(); ?>
    </div>
    <script>
        const gestureArea = document.getElementById('gesture-area');
        const fileInput = document.getElementById('file-input');
        const cameraInput = document.getElementById('camera-input');
        const imagePreview = document.getElementById('image-preview'); // Get the image element

        const body = document.body;
        const autoUploadCheckbox = document.getElementById('auto-upload-checkbox');

        fileInput.addEventListener('change', (event) => {
            const file = event.target.files; // Corrected to access the first file
            const reader = new FileReader();

            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }

            if (file) {
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


        const prevDayBtn = document.getElementById('prev-day-btn');
        const nextDayBtn = document.getElementById('next-day-btn');
        const currentDayBtn = document.getElementById('current-day-btn'); // Changed to button
        const day = document.getElementById('mealform-day'); // Changed to button
        let currentDate = new Date();
        let today = new Date(); // Store today's date
        currentDate.setDate(currentDate.getDate() - Math.abs(day.value));
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

            // Disable "next" button if currentDate is today
            nextDayBtn.disabled = (currentDate.toDateString() === today.toDateString());

            // Disable "prev" button if currentDate is 4 days before today
            prevDayBtn.disabled = (diff >= 4);
        }

        updateDayDisplay();

        prevDayBtn.addEventListener('click', () => {
            currentDate.setDate(currentDate.getDate() - 1);
            day.value--;
            updateDayDisplay();
        });

        nextDayBtn.addEventListener('click', () => {
            currentDate.setDate(currentDate.getDate() + 1);
            day.value++;
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
                    fileInput.removeAttribute('capture'); // Remove capture for long press
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
                    fileInput.setAttribute('capture', 'environment'); // Set capture for tap
                    fileInput.click();
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
    </script>
</div>