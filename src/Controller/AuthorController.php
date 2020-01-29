<?php


namespace App\Controller;


use App\Service\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AuthorController extends AbstractController
{
    /**
     * @Route("/authors", name="authors")
     * @param AuthorService $authorService
     * @return Response|void
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function authorList(AuthorService $authorService)
    {
        $authors = $authorService->getAuthors();

        return $this->render('author/list.html.twig', ['authors' => $authors]);
    }

    /**
     * @Route("/authors/{id}", name="author")
     *
     * @param AuthorService $authorService
     * @param $id
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getAuthor(AuthorService $authorService, $id)
    {
        $author = $authorService->getAuthor($id);

        return $this->render('author/single.html.twig', ['author' => $author]);
    }

    /**
     * @Route("/authors/{id}/delete", name="author_delete")
     *
     * @param $id
     */
    public function deleteAuthor($id)
    {

    }
}
