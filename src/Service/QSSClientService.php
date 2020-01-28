<?php


namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class QSSClientService
 * @package App\Service
 */
class QSSClientService
{
    public function getToken($email, $password)
    {
        $httpClient = HttpClient::createForBaseUri('https://symfony-skeleton.q-tests.com', ['http_version' => '2.0']);

        $response = $httpClient->request(
            Request::METHOD_POST,
            '/api/token', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode(
                [
                    'email' => $email,
                    'password' => $password
                ]
            )
        ]);

        if ($response->getStatusCode() == Response::HTTP_OK) {
            return $response;
        }

        return $response;
    }
}
