<?php

/** @var yii\web\View $this */

$this->title = 'Calorie Thingy';
$this->registerJsFile('@web/js/site.js');
?>

<header class="text-center py-5">
    <div class="container">
        <h1>Privacy Policy</h1>
        <p class="lead">Your privacy matters to us</p>
    </div>
</header>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">

            <h2>1. Information We Collect</h2>
            <p>When using CalorieThingy, we collect:</p>
            <ul>
                <li><strong>First Name:</strong> Stored for personalization.</li>
                <li><strong>Email Address:</strong> Used for account management.</li>
                <li><strong>Meal Photos:</strong> Stored indefinitely unless deleted by the user.</li>
                <li><strong>Caloric & Nutritional Info:</strong> AI-generated estimates.</li>
            </ul>

            <h2>2. How We Use Your Data</h2>
            <p>We use your data to:</p>
            <ul>
                <li>Analyze and track meals.</li>
                <li>Improve AI-based meal recognition.</li>
                <li>Allow you to view past meals.</li>
                <li>Send account-related notifications.</li>
            </ul>

            <h2>3. Data Storage & Security</h2>
            <ul>
                <li>Your data is securely stored in an encrypted database.</li>
                <li><strong>Meal photos</strong> are stored indefinitely.</li>
                <li>Security measures prevent unauthorized access.</li>
            </ul>

            <h2>4. Your Rights & Choices</h2>
            <p>You can:</p>
            <ul>
                <li>Request access to your data.</li>
                <li>Delete your meal photos and account info.</li>
                <li>Opt-out of non-essential data processing.</li>
            </ul>

            <h2>5. Third-Party Services</h2>
            <p>We use a paid version of <strong>Google Gemini AI</strong> for food analysis. No personally identifiable data is shared with Google.</p>

            <h2>6. Changes to This Policy</h2>
            <p>We may update this Privacy Policy. You will be notified of any significant changes.</p>

            <h2>7. Contact Us</h2>
            <p>If you have any questions, please contact us:</p>
            <ul>
                <li>Email: <span id="email"></span></li>
            </ul>

        </div>
    </div>
</div>
