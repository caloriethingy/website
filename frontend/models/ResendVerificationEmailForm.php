<?php

namespace frontend\models;

use common\jobs\EmailJob;
use Yii;
use common\models\User;
use yii\base\Model;

class ResendVerificationEmailForm extends Model
{
    /**
     * @var string
     */
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
                'filter' => ['status' => User::STATUS_UNVERIFIED],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends confirmation email to user
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        $user = User::findOne([
            'email' => $this->email,
            'status' => User::STATUS_UNVERIFIED
        ]);

        if ($user === null) {
            return false;
        }

        Yii::$app->queue->push(new EmailJob([
            'templateAlias' => EmailJob::VERIFY_EMAIL,
            'email' => $user->email,
            'templateModel' => [
                'name' => $user->first_name,
                "action_url" => Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]),
            ]
        ]));


        return true;
    }
}
