<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class BookService
 * @package App\Service
 */
class BookService
{
    const BOOK_URL = '/api/books';

    /**
     * @var QSSClientService
     */
    private $clientService;

    /**
     * BookService constructor.
     * @param QSSClientService $clientService
     */
    public function __construct(QSSClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * @param $id
     * @return bool
     * @throws TransportExceptionInterface
     */
    public function deleteBook($id)
    {
        $response = $this->clientService->sendDeleteRequest(self::BOOK_URL . '/' . $id);

        if (!empty($response) && $response->getStatusCode() === Response::HTTP_NO_CONTENT) {
            return true;
        }

        return false;
    }
}
