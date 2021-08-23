<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\FilmSearch;
use App\Form\FilmSearchType;
use App\Form\FilmType;
use App\Form\ParamsType;
use App\Repository\FilmRepository;
use App\Repository\ParamsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index(FilmRepository $filmRepo): Response
    {
        $films = $filmRepo->findAll();
        return $this->render('admin/index.html.twig', [
            'films' => $films
        ]);
    }

    /**
     * @Route("/alphabetique", name="admin_index_alpha")
     */
    public function indexAlpha(FilmRepository $filmRepo): Response
    {
        $films = $filmRepo->findAllByAlpha();
        return $this->render('admin/index.html.twig', [
            'films' => $films
        ]);
    }

    /**
     * @Route("/edit/{id}", name="film_edit")
     */
    public function edit(Film $film, Request $request): Response
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/edit-film.html.twig', [
            'film' => $film,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_film_delete", methods={"POST"})
     */
    public function delete(Request $request, Film $film): Response
    {
        if ($this->isCsrfTokenValid('delete' . $film->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/info/film/{id}", name="info_film")
     */
    public function info($id, FilmRepository $filmRepo, UserRepository $userRepo): Response
    {
        $film = $filmRepo->find($id);
        $users = $film->getUsers();

        $borrows = $film->getBorrows();

        $allUsers = $userRepo->findAll();

        return $this->render('admin/info-film.html.twig', [
            'film' => $film,
            'users' => $users,
            'allUsers' => $allUsers,
            'borrows' => $borrows
        ]);
    }

    /**
     * @Route("/user", name="admin_user")
     */
    public function user(UserRepository $userRepo): Response
    {
        $users = $userRepo->findAll();
        return $this->render('admin/user.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/new/film", name="new_film", methods={"GET"})
     */
    public function new(Request $request): Response
    {
        $search = new FilmSearch();
        $form = $this->createForm(FilmSearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $url = 'http://www.omdbapi.com/?apikey=4f1d0ddb&t=';
            $keyWords = $search->getKeyWord();
            $keyWords = urlencode($keyWords);
            $url .= $keyWords;

            $curl = curl_init();

            //on définit plusieurs options en une seule fois à la différence de curl_setopt()
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true, //le résultat sera sauvegardé dans la variable $response ci-dessous
                CURLOPT_TIMEOUT => 10, //nb de secondes à attendre avant abandon
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($curl);

            //true en second argument indique qu'on veut un tableau associatif
            $response = json_decode($response, true);

            if ($response['Response'] === "True") {
                $title = $response['Title'];
                $year = $response['Year'];
                $runtime = $response['Runtime'];
                $director = $response['Director'];
                $poster = $response['Poster'];
                $plot = $response['Plot'];

                $film = new Film();
                $film->setTitle($title);
                $film->setYear(intval($year));
                $film->setRuntime(intval($runtime));
                $film->setDirector($director);
                $film->setPoster($poster);
                $film->setPlot($plot);

                curl_close($curl);

                $this->container->get('session')->set('film', $film);
                return $this->redirectToRoute('confirm_new_film');
            } else {
                $message = 'Aucun résultat';
                return $this->render('admin/new-film.html.twig', [
                    'form' => $form->createView(),
                    'message' => $message
                ]);
            }
        }





        // $formFilm = $this->createForm(FilmType::class, $film);
        // $formFilm->handleRequest($request);

        // if ($formFilm->isSubmitted() and $formFilm->isValid()){
        //     $manager = $this->getDoctrine()->getManager();
        //     $manager->persist($film);
        //     $manager->flush();
        // }

        // return $this->render('admin/new-film.html.twig', [
        //     'formFilm' => $formFilm->createView(),
        //     'form' => $form->createView()
        // ]);


        return $this->render('admin/new-film.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new/film/confirm", name="confirm_new_film", methods={"GET"})
     */
    public function confirm(Request $request): Response
    {
        $film = $this->container->get('session')->get('film');
        if ($film === null || !$film instanceof Film) {
            return $this->createNotFoundException();
        }
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($film);
            $manager->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/confirm-new-film.html.twig', [
            'form' => $form->createView(),
            'film' => $film
        ]);
    }

    /**
     * @Route("/params", name="admin_params")
     */
    public function params(Request $request, ParamsRepository $paramsRepo): Response
    {
        $params = $paramsRepo->find(1);
        $form = $this->createForm(ParamsType::class, $params);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/params.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
