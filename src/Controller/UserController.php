<?php


namespace App\Controller;

use App\Service\QSSClientService;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param QSSClientService $clientService
     * @return Response|void
     */
    public function userLogin(Request $request, QSSClientService $clientService)
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            /**
             * @var Response $response
             */
            $response = $clientService->getToken($request->request->get('email'), $request->request->get('password'));

            return Response::create(json_encode($response->getContent()));

        }

        return $this->render('user/login.html.twig');
    }
}
