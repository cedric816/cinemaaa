<?php

namespace App\Controller;

use App\Entity\Sorting;
use App\Repository\FilmRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SortingType;

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

        $filmsQuery = $filmRepository->findAllQuery('title');
        $pagination = $paginator->paginate(
            $filmsQuery,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/year", name="user_index_year")
     */
    public function indexByYear(FilmRepository $filmRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        $filmsQuery = $filmRepository->findAllQuery('year');
        $pagination = $paginator->paginate(
            $filmsQuery,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/duration", name="user_index_duration")
     */
    public function indexByDuration(FilmRepository $filmRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        $filmsQuery = $filmRepository->findAllQuery('runtime');
        $pagination = $paginator->paginate(
            $filmsQuery,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
        ]);
    }
}