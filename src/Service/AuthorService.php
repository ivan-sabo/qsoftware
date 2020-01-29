<?php


namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class AuthorService
 * @package App\Service
 */
class AuthorService
{
    const AUTHOR_URI = '/api/authors';

    /**
     * @var QSSClientService
     */
    private $clientService;

    public function __construct(QSSClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * @return ArrayCollection
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getAuthors()
    {
        $response = $this->clientService->sendGetRequest(self::AUTHOR_URI);

        $authorsRaw = json_decode($response->getContent(), true);
        $authorsCollection = new ArrayCollection();

        foreach ($authorsRaw as $authorRaw) {
            $author = new Author();
            $author->setId($authorRaw['id']);
            $author->setFirstName($authorRaw['first_name']);
            $author->setLastName($authorRaw['last_name']);
            $author->setBirthday($authorRaw['birthday']);
            $author->setGender($authorRaw['gender']);
            $author->setPlaceOfBirth($authorRaw['place_of_birth']);

            $authorsCollection->add($author);
        }

        return $authorsCollection;
    }

    /**
     * @param $id
     * @return Author
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getAuthor($id)
    {
        $response = $this->clientService->sendGetRequest(self::AUTHOR_URI . '/' . $id);

        $authorRaw = json_decode($response->getContent(), true);

        $author = new Author();
        $author->setId($authorRaw['id']);
        $author->setFirstName($authorRaw['first_name']);
        $author->setLastName($authorRaw['last_name']);
        $author->setBirthday($authorRaw['birthday']);
        $author->setGender($authorRaw['gender']);
        $author->setPlaceOfBirth($authorRaw['place_of_birth']);

        foreach ($authorRaw['books'] as $bookRaw) {
            $book = new Book();

            $book->setId($bookRaw['id']);
            $book->setFormat($bookRaw['format']);
            $book->setIsbn($bookRaw['isbn']);
            $book->setNumberOfPages($bookRaw['number_of_pages']);
            $book->setReleaseDate($bookRaw['release_date']);
            $book->setTitle($bookRaw['title']);
            $book->setUpdatedAt($bookRaw['updated_at']);

            $author->addBook($book);
        }

        return $author;
    }

    public function deleteAuthor($id)
    {

    }
}
