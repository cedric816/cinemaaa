<?php

namespace App\Controller;

use App\Entity\Cart;
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
use App\Repository\CartRepository;

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
    public function add($id, FilmRepository $filmRepository): Response
    {

        $user = $this->getUser();
        $carts = $user->getCarts();
        $cart = $carts[0];

        if (is_null($cart)) {
            $cart = new Cart();
            $user->addCart($cart);
            $this->getDoctrine()->getManager()->persist($cart);
            $this->getDoctrine()->getManager()->flush();
        }

        $selectFilm = $filmRepository->find($id);

        $count = 0;
        $count = $cart->addFilm($selectFilm, $count);

        if ($count == 1) {
            $quantity = $selectFilm->getQuantity();
            $selectFilm->setQuantity($quantity - 1);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/user/cart", name="user_cart")
     */
    public function cart(): Response
    {
        $user = $this->getUser();
        $cart = $user->getCarts();
        $cart = $cart[0];

        if (is_null($cart)) {
            $cart = new Cart();
            $user->addCart($cart);
            $this->getDoctrine()->getManager()->persist($cart);
            $this->getDoctrine()->getManager()->flush();
        }

        $films = $cart->getFilms();
        return $this->render('user/cart.html.twig', [
            'films' => $films
        ]);
    }
}
