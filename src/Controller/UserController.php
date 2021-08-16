<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index")
     */
    public function index(FilmRepository $filmRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        $filmsQuery = $filmRepository->findAllQuery();
        $pagination = $paginator->paginate(
            $filmsQuery,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'user' => $user
        ]);
    }
}