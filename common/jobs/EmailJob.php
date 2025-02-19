<?php

namespace common\jobs;

use Yii;
use yii\base\BaseObject;
use yii\queue\RetryableJobInterface;

class EmailJob extends BaseObject implements RetryableJobInterface
{

    const MAX_ATTEMPTS = 3;
    const TTR = 600; // in seconds

    public const PASSWORD_RESET = 'password-reset';
    public const PASSWORD_HAS_BEEN_RESET = 'password-has-been-reset';
    public const WELCOME_EMAIL = 'welcome-email';
    public const VERIFY_EMAIL = 'verify-email';
    public const ADMIN_NOTIFY = 'admin-new-user';
    public const PRICE_CHANGE = 'price-change';
    public const PAYOUT_NOTIFY = 'payout-notify';
    public array $templateModel;
    public string $email;
    public string $templateAlias;

    /**
     * @inheritDoc
     */
    public function execute($queue)
    {
        // Merge these values which are always on emails
        $this->templateModel = array_merge($this->templateModel, [
            "product" => Yii::$app->params['product_name'],
            "product_name" => Yii::$app->params['product_name'],
            "support_url" => Yii::$app->params['support_url'],
            "product_url" => Yii::$app->urlManager->createAbsoluteUrl(['site/index']),
            "company_name" => Yii::$app->params['company_name'],
            "company_address" => Yii::$app->params['company_address'],
        ]);

        Yii::$app->postmark->client->sendEmailWithTemplate(
            Yii::$app->params['supportEmail'],
            $this->email,
            $this->templateAlias,
            $this->templateModel,
            messageStream: Yii::$app->params['postmark.messageStream']
        );
    }

    /**
     * @inheritDoc
     */
    public function getTtr()
    {
        return self::TTR;
    }

    /**
     * @inheritDoc
     */
    public function canRetry($attempt, $error)
    {
        return ($attempt < self::MAX_ATTEMPTS);
    }
}