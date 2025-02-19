<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Confetti assett.
 */
class ConfettiAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        '//cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js',
    ];
    public $depends = [
    ];
}
