<?php

namespace api\controllers;

use common\models\User;
use Firebase\JWT\JWT;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function verbs()
    {
        return [
            'register' => ['POST'],
            'login' => ['POST'],
        ];
    }

    private function generateJwt($user)
    {
        $key = Yii::$app->params['jwtSecret']; // Set in params.php
        $payload = [
            'iss' => 'calorie-thingy',
            'aud' => 'calorie-thingy',
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7), // 7 days expiration
            'sub' => $user->auth_key,
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public function actionRegister()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();

        if (empty($data['email']) || empty($data['password'])) {
            return ['error' => 'Email and password are required'];
        }

        if (User::findOne(['email' => $data['email']])) {
            return ['error' => 'Email already exists'];
        }

        $user = new User();
        $user->email = $data['email'];
        $user->password_hash = Yii::$app->security->generatePasswordHash($data['password']);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        if ($user->save()) {
            return ['token' => $this->generateJwt($user)];
        }

        return ['error' => 'Registration failed'];
    }

    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();

        $user = User::findOne(['email' => $data['email'] ?? null]);
        if (!$user || !Yii::$app->security->validatePassword($data['password'], $user->password_hash)) {
            throw new UnauthorizedHttpException();
            return ['error' => 'Invalid credentials'];
        }
        // @todo not sure if it makes sense to generate an auth key each login via the API?
        $user->generateAuthKey();
        $user->save();

        return ['token' => $this->generateJwt($user)];
    }e
}