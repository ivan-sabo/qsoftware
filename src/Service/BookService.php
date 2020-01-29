<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @param Request $request
     * @return bool
     * @throws TransportExceptionInterface
     */
    public function addBookFromRequest(Request $request)
    {
        $bookBody = [];

        $bookBody['author'] = [
            'id' => (integer)$request->request->get('author')
        ];
        $bookBody['title'] = $request->request->get('title');
        $bookBody['release_date'] = $request->request->get('release_date');
        $bookBody['updated_at'] = $request->request->get('updated_at');
        $bookBody['description'] = $request->request->get('description');
        $bookBody['isbn'] = $request->request->get('isbn');
        $bookBody['format'] = $request->request->get('format');
        $bookBody['number_of_pages'] = (integer)$request->request->get('number_of_pages');

        $response = $this->clientService->sendPostRequest(self::BOOK_URL, $bookBody);

        if (!empty($response) && $response->getStatusCode() === Response::HTTP_OK) {
            return true;
        }

        return false;
    }
}
