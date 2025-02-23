<?php

/** @var yii\web\View $this */

$this->title = 'Calorie Thingy';
$this->registerJsFile('@web/js/site.js');
?>

<header class="text-center py-5">
    <div class="container">
        <h1>Terms & Conditions</h1>
        <p class="lead">Please read these terms carefully before using CalorieThingy.</p>
    </div>
</header>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">

            <h2>1. Introduction</h2>
            <p>Welcome to <strong>Calorie Thingy</strong>. By accessing or using our app, you agree to these Terms and Conditions.</p>

            <h2>2. Use of Our Services</h2>
            <p>You agree to use CalorieThingy for personal and non-commercial purposes only. You must not misuse or abuse our services in any way.</p>

            <h2>3. User Accounts</h2>
            <ul>
                <li>You must provide accurate and up-to-date information when creating an account.</li>
                <li>You are responsible for maintaining the confidentiality of your login credentials.</li>
                <li>We reserve the right to terminate accounts for violations of these terms.</li>
            </ul>

            <h2>4. Meal Photos & Data Storage</h2>
            <ul>
                <li>Meal photos you upload will be stored indefinitely unless deleted by you.</li>
                <li>We use AI-powered food analysis (via Google Gemini API) to provide nutritional insights.</li>
                <li>Your personal data is stored securely and is not shared with third parties without consent.</li>
            </ul>

            <h2>5. Limitation of Liability</h2>
            <p>We do not guarantee the accuracy of AI-generated nutritional data. You are responsible for verifying any health or diet-related decisions.</p>

            <h2>6. Changes to These Terms</h2>
            <p>We may update these Terms at any time. Continued use of the app constitutes acceptance of the new Terms.</p>

            <h2>7. Contact Us</h2>
            <p>If you have any questions, please contact us:</p>
            <ul>
                <li>Email: <span id="email"></span></li>
            </ul>

        </div>
    </div>
</div>
