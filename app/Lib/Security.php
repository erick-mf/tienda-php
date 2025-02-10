<?php

namespace App\Lib;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Security
{
    final public static function secretKey()
    {
        return $_ENV['SECRET_KEY'];
    }

    final public static function createToken(array $data)
    {
        $time = strtotime('now');
        $exp = $time + 1200;
        $token = [
            'iat' => $time,
            'exp' => $exp,
            'data' => $data,
        ];

        return ['token' => JWT::encode($token, self::secretKey(), 'HS256'),
            'exp' => $exp,
        ];
    }

    final public static function createTokenWhithoutExpiration($data)
    {
        $time = strtotime('now');
        $token = [
            'iat' => $time,
            'data' => $data,
        ];

        return JWT::encode($token, self::secretKey(), 'HS256');
    }

    final public static function getToken()
    {
        $response = [];
        $headers = apache_request_headers();
        if (! isset($headers['Authorization'])) {
            return $response['message'] = json_decode(http_response_code(403));
        }
        try {
            $authorizationArr = explode(' ', $headers['Authorization']);
            $token = $authorizationArr[1];

            return JWT::decode($token, new Key(Security::secretKey(), 'HS256'));
        } catch (Exception $e) {
            return $response['message'] = json_encode(http_response_code(401));
        }
    }

    final public static function validateToken($token)
    {
        $errors = [];
        try {
            return JWT::decode($token, new Key(self::secretKey(), 'HS256'));
        } catch (Exception $e) {
            $page = new Pages;
            $errors['general'] = 'Token invalido o a expirado';
            $page->render('auth/login', ['errors' => $errors]);
        }
    }
}
