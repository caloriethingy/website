<?php

namespace frontend\models;

use common\jobs\EmailJob;
use Yii;
use yii\base\Model;
use common\models\User;

use const donatj\UserAgent\BROWSER;
use const donatj\UserAgent\PLATFORM;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return true;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return true;
            }
        }

        try {
            $uaInfo = \donatj\UserAgent\parse_user_agent();
        } catch (\Exception $e) {
            $uaInfo[PLATFORM] = 'unknown';
            $uaInfo[BROWSER] = 'unknown';
        }

        Yii::$app->queue->push(new EmailJob([
            'templateAlias' => EmailJob::PASSWORD_RESET,
            'email' => $this->email,
            'templateModel' => [
                'name' => $user->first_name,
                "operating_system" => $uaInfo[PLATFORM],
                "action_url" => Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]),
                "browser_name" => $uaInfo[BROWSER],
            ]
        ]));

        return true;
    }
}