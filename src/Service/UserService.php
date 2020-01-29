<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UserService
{
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
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getUser($email, $password)
    {
        $userDataResponse = $this->qssClientService->getUserData($email, $password);

        if (empty($userDataResponse)) {
            return null;
        }

        $user = $this->createAndStoreFromResponse($userDataResponse);

        return $user;
    }

    /**
     * @param $token
     * @return User
     */
    public function getUserByToken($token)
    {
        /**
         * @var UserRepository $userRepo
         */
        $userRepo = $this->em->getRepository(User::class);

        /**
         * @var User $user
         */
        $user = $userRepo->findOneBy(['token' => $token]);

        return $user;
    }

    /**
     * @param ResponseInterface $response
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function createAndStoreFromResponse(ResponseInterface $response)
    {
        $user = new User();

        $responseArray = json_decode($response->getContent(), true);

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
}
