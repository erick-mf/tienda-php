<?php

namespace App\Lib;

use App\Repositories\UserRepository;

/**
 * Clase ResponseHttp
 *
 * Esta clase proporciona métodos estáticos para manejar respuestas HTTP y autenticación de API.
 */
class ResponseHttp
{
    /**
     * Envía una respuesta JSON con código de estado HTTP.
     *
     * @param  int  status  Código de estado HTTP
     * @param  string  res  Mensaje de respuesta
     * @param  array|null  $body  Datos opcionales
     */
    final public static function statusMessages(int $status, string $res, ?array $body = null)
    {
        http_response_code($status);

        $msg = [
            'status' => self::getStatusMessage($status),
            'message' => $res,
        ];

        if ($body !== null) {
            $msg['body'] = $body;
        }

        echo json_encode($msg);
        exit;
    }

    /**
     * Obtiene el mensaje de estado correspondiente a un código HTTP.
     *
     * @param  int  $code  Código de estado HTTP
     * @return string Mensaje de estado correspondiente
     */
    public static function getStatusMessage($code)
    {
        $statusMessage = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'Ok',
            201 => 'Created',
            202 => 'Accepted',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
        ];

        return $statusMessage[$code] ?? $statusMessage[500];
    }

    /**
     * Establece las cabeceras HTTP para la respuesta de la API.
     */
    public static function setHeaders()
    {
        header_remove();
        header('Access-Control-Allow-Headers: Content-Type, token, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        header('Content-Type: application/json; charset=UTF-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Max-Age: 3600');
    }

    /**
     * Autentica la solicitud de API.
     *
     * Verifica el token de autorización y el rol de administrador.
     *
     * @return bool Retorna true si la autenticación es exitosa
     */
    public static function authAPI()
    {
        $headers = apache_request_headers();
        ucfirst($headers['authorization']);

        if (! isset($headers['Authorization']) || empty($headers['Authorization'])) {
            self::statusMessages(403, 'No tienes permisos para esta acción');
            exit;
        }

        $token = self::extractToken($headers['Authorization']);

        if ($token === null) {
            self::statusMessages(401, 'Formato de token inválido');
            exit;
        }

        $userRepository = new UserRepository;
        $user = $userRepository->getToken($token);

        if ($user === null) {
            self::statusMessages(401, 'Token no válido');
            exit;
        }

        if ($user['rol'] !== 'admin') {
            self::statusMessages(403, 'No tienes permisos de administrador para esta acción');
            exit;
        }
    }

    private static function extractToken($authHeader)
    {
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $matches[1];
        }

        // Si no encuentra el formato Bearer, intenta extraer el token directamente
        $parts = explode(' ', $authHeader, 2);
        if (count($parts) == 2) {
            return $parts[1];
        }

        return null;
    }
}
