<?php


namespace App\Controller;


use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class BookController
 * @package App\Controller
 */
class BookController extends AbstractController
{
    /**
     * @Route("/books/{id}/delete", name="book_delete")
     *
     * @param BookService $bookService
     * @param $id
     * @return RedirectResponse
     * @throws TransportExceptionInterface
     */
    public function deleteBook(BookService $bookService, $id)
    {
        $bookService->deleteBook($id);

        return $this->redirectToRoute('index');
    }
}
