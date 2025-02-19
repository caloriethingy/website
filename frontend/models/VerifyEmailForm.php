<?php

namespace frontend\models;

use common\jobs\EmailJob;
use common\models\User;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;

class VerifyEmailForm extends Model
{
    /**
     * @var string
     */
    public $token;

    /**
     * @var User
     */
    private $_user;


    /**
     * Creates a form model with given token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, array $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Verify email token cannot be blank.');
        }
        $this->_user = User::findByVerificationToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException('Wrong verify email token.');
        }
        parent::__construct($config);
    }

    /**
     * Verify email
     *
     * @return User|null the saved model or null if saving fails
     */
    public function verifyEmail()
    {
        $user = $this->_user;
        $user->status = User::STATUS_VERIFIED;

        Yii::$app->queue->push(new EmailJob([
            'templateAlias' => EmailJob::ADMIN_NOTIFY,
            "email" => Yii::$app->params['adminEmail'],
            'templateModel' => [
                "user_name" => $user->email,
                "action_url" => Yii::$app->urlManager->createAbsoluteUrl(['user/update', 'id' => $user->id, 'approve' => 1]),
                "action_edit_url" => Yii::$app->urlManager->createAbsoluteUrl(['user/update', 'id' => $user->id]),
            ]
        ]));

        return $user->save(false) ? $user : null;
    }
}
