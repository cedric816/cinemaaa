<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/login")
 */
class LoginCheckController extends AbstractController
{
    /**
     * @Route("/check", name="login_check")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $role = $user->getRoles();
        $role = $role[0];
        if ($role === "ROLE_USER"){
            return $this->redirectToRoute("user_index");
        } elseif ($role === "ROLE_ADMIN"){
            return $this->redirectToRoute("admin_index");
        }
    }
}