<?php


namespace App\Controller;


use App\Service\AuthorService;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

        return $this->redirectToRoute('authors');
    }

    /**
     * @Route("/books/add", name="book_add")
     *
     * @param AuthorService $authorService
     * @param BookService $bookService
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public function addBook(AuthorService $authorService, BookService $bookService, Request $request)
    {
        $authors = $authorService->getAuthors();

        if ($request->isMethod(Request::METHOD_POST)) {
            $bookService->addBookFromRequest($request);

            return $this->redirectToRoute('authors');
        }

        return $this->render('book/add.html.twig', ['authors' => $authors]);
    }
}
