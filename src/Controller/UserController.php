<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Film;
use App\Entity\FilmSearch;
use App\Form\FilmSearchType;
use App\Repository\CartRepository;
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
        $cart = $user->getActiveCart();

        if (is_null($cart)) {
            $cart = new Cart();
            $cart->setIsActive(true);
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
     * @Route("/cart", name="user_cart")
     */
    public function cart(): Response
    {
        $user = $this->getUser();
        $cart = $user->getActiveCart();

        if (is_null($cart)) {
            $cart = new Cart();
            $cart->setIsActive(true);
            $user->addCart($cart);
            $this->getDoctrine()->getManager()->persist($cart);
            $this->getDoctrine()->getManager()->flush();
        }

        $films = $cart->getFilms();
        return $this->render('user/cart.html.twig', [
            'films' => $films,
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/cart/{id}", name="film_delete", methods={"POST"})
     */
    public function delete(Request $request, Film $film): Response
    {
        if ($this->isCsrfTokenValid('delete' . $film->getId(), $request->request->get('_token'))) {
            $user = $this->getUser();
            $cart = $user->getActiveCart();
            $cart->removeFilm($film);
            $quantity = $film->getQuantity();
            $film->setQuantity($quantity + 1);
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
        }

        return $this->redirectToRoute('user_cart', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/cart/validation", name="cart_validation")
     */
    public function validation(): Response
    {
        $user = $this->getUser();
        $cart = $user->getActiveCart();
        $cart->setIsActive(false);

        $films = $cart->getFilms();
        foreach ($films as $film){
            $user->addFilmsNotRender($film);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/cart/history", name="cart_history")
     */
    public function history(): Response
    {
        $user = $this->getUser();
        $carts = $user->getCarts();

        return $this->render('user/history.html.twig', [
            'carts' => $carts
        ]);
    }

    /**
     * @Route("/history/{id}", name="history_detail")
     */
    public function detail($id, CartRepository $cartRepo): Response
    {
        $cart = $cartRepo->find($id);
        return $this->render('user/history-detail.html.twig', [
            'cart' => $cart
        ]);
    }

    /**
     * @Route("return/{idCart}/{idFilm}", name="return_film")
     */
    public function return($idCart, $idFilm, CartRepository $cartRepo, FilmRepository $filmrepo): Response
    {
        $user = $this->getUser();
        $cart = $cartRepo->find($idCart);
        $film = $filmrepo->find($idFilm);
        $cart->removeFilm($film);
        $user->removeFilmsNotRender($film);
        $user->addFilm($film);
        $quantity = $film->getQuantity();
        $film->setQuantity($quantity+=1);
        $user->addFilm($film);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('history_detail', ['id'=>$cart->getId()]);
    }
}
