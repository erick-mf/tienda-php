<?php

namespace App\Controllers;

use Exception;

/**
 * Clase PayPalController
 *
 * Esta clase maneja las operaciones relacionadas con la integración de PayPal.
 */
class PayPalController
{
    private string $baseUrl = 'https://api-m.sandbox.paypal.com';

    private function getHost()
    {
        $host = $_ENV['HOST_URL'];

        return $host;
    }

    /**
     * Obtiene el token de acceso para la API de PayPal.
     *
     * @return string El token de acceso.
     */
    private function getAccessToken()
    {
        $token = $_ENV['TOKEN_PAYPAL'];

        return $token;
    }

    /**
     * Crea una nueva orden de pago en PayPal.
     *
     * Este método crea una orden de pago basada en los items del carrito en la sesión,
     * y redirige al usuario a la página de aprobación de PayPal.
     */
    public function createOrder()
    {
        try {
            $accessToken = $this->getAccessToken();
            $host = $this->getHost();

            $total = 0;
            foreach ($_SESSION['order'] as $item) {
                $total += $item['precio'] * $item['cantidad'];
            }

            $body = json_encode([
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => number_format($total, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'return_url' => "$host/paypal/capture-order",
                    'cancel_url' => "$host/cart",
                ],
            ]);

            $ch = curl_init("{$this->baseUrl}/v2/checkout/orders");
            curl_setopt_array($ch, [
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    "Authorization: Bearer $accessToken",
                ],
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_RETURNTRANSFER => true,
            ]);

            $response = curl_exec($ch);
            if ($response === false) {
                throw new Exception(curl_error($ch));
            }

            $responseData = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error al decodificar la respuesta de PayPal');
            }

            if (isset($responseData['id']) && isset($responseData['links'])) {
                foreach ($responseData['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        header('Location: '.$link['href']);
                        exit;
                    }
                }
            }

            throw new Exception('No se encontró el enlace de aprobación en la respuesta de PayPal');
        } catch (Exception $e) {
            error_log('Error en PayPal createOrder: '.$e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear la orden de PayPal: '.$e->getMessage()]);
            exit;
        } finally {
            if (isset($ch)) {
                curl_close($ch);
            }
        }
    }

    /**
     * Captura una orden de pago aprobada en PayPal.
     *
     * Este método se llama después de que el usuario aprueba el pago en PayPal.
     * Captura la orden y procesa el resultado.
     */
    public function captureOrder()
    {
        try {
            $token = $_GET['token'] ?? null;
            $payerId = $_GET['PayerID'] ?? null;

            if (! $token || ! $payerId) {
                throw new Exception('Parámetros de PayPal faltantes');
            }

            $accessToken = $this->getAccessToken();

            $ch = curl_init("{$this->baseUrl}/v2/checkout/orders/{$token}/capture");
            curl_setopt_array($ch, [
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    "Authorization: Bearer $accessToken",
                ],
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
            ]);

            $response = curl_exec($ch);
            if ($response === false) {
                throw new Exception(curl_error($ch));
            }

            $data = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error al decodificar la respuesta de PayPal');
            }

            if (isset($data['status']) && $data['status'] === 'COMPLETED') {
                $_SESSION['message'] = ['text' => 'Pago exitoso', 'type' => 'success-message'];
                unset($_SESSION['order']);
            } else {
                throw new Exception('El pago no se completó: '.($data['message'] ?? 'Estado desconocido'));
            }

            header('Location: /');
            exit;

        } catch (Exception $e) {
            error_log('Error en PayPal captureOrder: '.$e->getMessage());
            $_SESSION['message'] = ['text' => 'Error en el pago: '.$e->getMessage(), 'type' => 'error-message'];
            header('Location: /cart');
            exit;
            curl_close($ch);
        }
    }
}
