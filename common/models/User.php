<?php

namespace common\models;

use common\jobs\EmailJob;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $verification_token
 * @property string  $email
 * @property string  $first_name
 * @property string  $name
 * @property string  $auth_key
 * @property integer $status
 * @property integer $sales_agent_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    // User statuses
    public const STATUS_UNVERIFIED = 10;
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_VERIFIED = 2;

    public const PAYOUT_INTERVAL_MONTHLY = 0;
    public array $userStatusArray;
    public string $role = '';
    public bool $welcomeEmailSent = false;
    public string $firstName;


    public function init()
    {
        parent::init();

        $this->userStatusArray = [
            self::STATUS_UNVERIFIED => Yii::t('app', 'Unverified'),
            self::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_VERIFIED => Yii::t('app', 'Verified (not active)'),
        ];

        // register event
        //$this->on(self::EVENT_AFTER_INSERT, [$this, 'emailTrigger']);
        //$this->on(self::EVENT_AFTER_UPDATE, [$this, 'emailTrigger']);
    }


    public function emailTrigger(AfterSaveEvent $event)
    {
        Yii::$app->queue->push(new EmailJob([
            'templateAlias' => EmailJob::WELCOME_EMAIL,
            'email' => $event->sender->email,
            'templateModel' => [
                "name" => $event->sender->first_name,
                "action_url" => Yii::$app->urlManager->createAbsoluteUrl(['site/login']),
            ]
        ]));

        $event->sender->welcome_email_sent = true;
        $event->sender->save(false);
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['created_at', 'updated_at'], 'integer'],
            [
                'status',
                'in',
                'range' => [
                    self::STATUS_ACTIVE,
                    self::STATUS_UNVERIFIED,
                    self::STATUS_VERIFIED,
                    self::STATUS_INACTIVE,
                ]
            ],
            [['role'], 'string'],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $auth = Yii::$app->authManager;

        // delete exiting roles if set
        $auth->revokeAll($this->id);
        // assign new role
        if (!empty($this->role)) {
            $auth->assign($auth->getRole($this->role), $this->id);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        $rolesAssignedToUser = Yii::$app->authManager->getRolesByUser($this->id);
        // we only use one role for the user
        if (!empty($rolesAssignedToUser)) {
            $this->role = array_key_first($rolesAssignedToUser);
        }

        parent::afterFind();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * Get names for dropdown lists
     *
     * @param $dropdown
     * @return array|mixed
     */
    public function getStatusName($dropdown = false)
    {
        return $dropdown ? $this->userStatusArray : $this->userStatusArray[$this->status];
    }

    public function getAuthAssignment()
    {
        return $this->hasOne(\common\models\AuthAssignment::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return ActiveRecord|array|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return ActiveRecord|array|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(['password_reset_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_UNVERIFIED
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Gets query for [[SalesAgent]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getSalesAgent()
    {
        return $this->hasOne(SalesAgent::class, ['id' => 'sales_agent_id']);
    }
}