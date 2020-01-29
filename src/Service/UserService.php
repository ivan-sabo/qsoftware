<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UserService
{
    const TOKEN_URL = '/api/token';
    /**
     * @var QSSClientService
     */
    private $qssClientService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, QSSClientService $clientService)
    {
        $this->em = $em;
        $this->qssClientService = $clientService;
    }

    /**
     * @param $email
     * @param $password
     * @return User|object|null
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getUser($email, $password)
    {
        $userDataResponse = $this->qssClientService->getUserData($email, $password);

        if (empty($userDataResponse)) {
            return null;
        }

        $user = $this->createOrUpdateFromResponse($userDataResponse);

        return $user;
    }

    /**
     * @param ResponseInterface $response
     * @return User
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     */
    private function createOrUpdateFromResponse(ResponseInterface $response)
    {
        $responseArray = json_decode($response->getContent(), true);

        /**
         * @var UserRepository $userRepo
         */
        $userRepo = $this->em->getRepository(User::class);

        /**
         * @var User|null $user
         */
        $user = $userRepo->findOneBy(['email' => $responseArray['user']['email']]);

        if (!empty($user)) {
            $user->setTokenKey($responseArray['token_key']);

            $this->em->flush();

            return $user;
        }

        $user->setActive(true);
        $user->setTokenKey($responseArray['token_key']);
        $user->setEmail($responseArray['user']['email']);
        $user->setFirstName($responseArray['user']['first_name']);
        $user->setLastName($responseArray['user']['last_name']);
        $user->setGender($responseArray['user']['gender']);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @param $email
     * @param $password
     * @return |null
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getTokenForUser($email, $password)
    {
        $response = $this->qssClientService->getUserData($email, $password);

        if (empty($response)) {
            return null;
        }

        $responseArray = json_decode($response->getContent(), true);

        return $responseArray['token_key'];
    }
}
