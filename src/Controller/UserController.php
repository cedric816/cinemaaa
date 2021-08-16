<?php

namespace App\Controller;

use App\Entity\FilmSearch;
use App\Entity\Sorting;
use App\Form\FilmSearchType;
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

        $search = new FilmSearch();
        $form = $this->createForm(FilmSearchType::class, $search);
        $form->handleRequest($request);

        $filmsQuery = $filmRepository->findAllQuery('title', $search);
        $pagination = $paginator->paginate(
            $filmsQuery,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/year", name="user_index_year")
     */
    public function indexByYear(FilmRepository $filmRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        $search = new FilmSearch();
        $form = $this->createForm(FilmSearchType::class, $search);
        $form->handleRequest($request);

        $filmsQuery = $filmRepository->findAllQuery('year', $search);
        $pagination = $paginator->paginate(
            $filmsQuery,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/duration", name="user_index_duration")
     */
    public function indexByDuration(FilmRepository $filmRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        $search = new FilmSearch();
        $form = $this->createForm(FilmSearchType::class, $search);
        $form->handleRequest($request);

        $filmsQuery = $filmRepository->findAllQuery('runtime', $search);
        $pagination = $paginator->paginate(
            $filmsQuery,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/add/{id}", name="user_add_film")
     */
    public function add($id, FilmRepository $filmRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        $search = new FilmSearch();
        $form = $this->createForm(FilmSearchType::class, $search);
        $form->handleRequest($request);

        $filmsQuery = $filmRepository->findAllQuery('title', $search);
        $pagination = $paginator->paginate(
            $filmsQuery,
            $request->query->getInt('page', 1),
            3
        );

        $selectFilm = $filmRepository->find($id);
        $quantity = $selectFilm->getQuantity();
        $selectFilm->setQuantity($quantity-1);

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
            'form' => $form->createView(),
            'selectFilm' => $selectFilm
        ]);
    }
}