<?php

namespace api\components;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;
use yii\filters\auth\AuthMethod;
use yii\web\UnauthorizedHttpException;

class JwtAuth extends AuthMethod
{
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $jwt = $matches[1];
            try {
                $decoded = JWT::decode($jwt, new Key(Yii::$app->params['jwtSecret'], 'HS256'));
                return $user->loginByAccessToken($decoded->sub);
            } catch (\Exception $e) {
                throw new UnauthorizedHttpException('Invalid token');
            }
        }

        return null;
    }
}