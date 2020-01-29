<?php


namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class QSSClientService
 * @package App\Service
 */
class QSSClientService
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        /**
         * @todo This should be DI, but it looks like there is a bug in HttpClient... Needs test
         * Exception: Undefined index: http_method
         * Something like this: https://github.com/symfony/symfony/issues/34365
         */
        $this->httpClient = HttpClient::createForBaseUri('https://symfony-skeleton.q-tests.com', [
            'http_version' => '2.0'
        ]);

        $this->security = $security;
    }

    /**
     * @param $email
     * @param $password
     * @return ResponseInterface|null
     * @throws TransportExceptionInterface
     */
    public function getUserData($email, $password)
    {
        $response = $this->httpClient->request(
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

        return null;
    }

    /**
     * @param $url
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function sendGetRequest($url)
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        $response = $this->httpClient->request(
            Request::METHOD_GET,
            $url,
            [
                'auth_bearer' => $user->getTokenKey()
            ]
        );

        if ($response->getStatusCode() == Response::HTTP_OK) {
            return $response;
        }

        return null;
    }

    /**
     * @param $url
     * @return ResponseInterface|null
     * @throws TransportExceptionInterface
     */
    public function sendDeleteRequest($url)
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        $response = $this->httpClient->request(
            Request::METHOD_DELETE,
            $url,
            [
                'auth_bearer' => $user->getTokenKey()
            ]
        );

        if ($response->getStatusCode() == Response::HTTP_NO_CONTENT) {
            return $response;
        }

        return null;
    }

    /**
     * @param $url
     * @param $body
     * @return ResponseInterface|null
     * @throws TransportExceptionInterface
     */
    public function sendPostRequest($url, $body)
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        $response = $this->httpClient->request(
            Request::METHOD_POST,
            $url,
            [
                'auth_bearer' => $user->getTokenKey(),
                'json' => $body,
            ]
        );

        if ($response->getStatusCode() == Response::HTTP_OK) {
            return $response;
        }

        return null;
    }

    /**
     * @param $url
     * @param $body
     * @param $token
     * @return ResponseInterface|null
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public function sentPostRequestWithToken($url, $body, $token)
    {
        $response = $this->httpClient->request(
            Request::METHOD_POST,
            $url,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($body),
                'auth_bearer' => $token,
            ]
        );

        if ($response->getStatusCode() == Response::HTTP_OK) {
            return $response;
        }

        return null;
    }
}
