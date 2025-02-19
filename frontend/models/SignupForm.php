<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\jobs\EmailJob;


/**
 * Signup form
 */
class SignupForm extends Model
{
    public $first_name;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email','first_name'], 'trim'],
            [['email','first_name'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save(false);

        // the following three lines were added:
        $auth = \Yii::$app->authManager;
        $salesAgentRole = $auth->getRole('user');
        $auth->assign($salesAgentRole, $user->getId());

        return $this->sendEmail($user);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        Yii::$app->queue->push(new EmailJob([
            'templateAlias' => EmailJob::VERIFY_EMAIL,
            'email' => $user->email,
            'templateModel' => [
                "name" => $user->first_name,
                "action_url" => Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]),
            ]
        ]));

        return true;
    }
}
