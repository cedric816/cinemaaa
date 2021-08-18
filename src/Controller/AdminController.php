<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use App\Repository\FilmRepository;
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
        $films = $filmRepo -> findAll();
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
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/info/{id}", name="info_film")
     */
    public function info($id, FilmRepository $filmRepo, UserRepository $userRepo): Response
    {      
        $film = $filmRepo->find($id);
        $users = $film->getUsers();

        $allUsers = $userRepo -> findAll();
       
        return $this->render('admin/info-film.html.twig', [
            'film' => $film,
            'users' => $users,
            'allUsers' => $allUsers
        ]);
    }

    /**
     * @Route("/user", name="admin_user")
     */
    public function user(UserRepository $userRepo): Response
    {
        $users = $userRepo -> findAll();
        return $this->render('admin/user.html.twig', [
            'users' => $users
        ]);
    }
}