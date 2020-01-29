<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/login", name="user_login")
     * @return Response|void
     */
    public function userLogin()
    {
        return $this->render('user/login.html.twig');
    }

    /**
     * @Route("/logout", name="user_logout", methods={"GET"})
     */
    public function userLogout()
    {
        return $this->redirectToRoute('user_login');
    }
}
