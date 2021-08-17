<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     */
    public function index(FilmRepository $filmRepo): Response
    {
        $lastFilms = $filmRepo -> findLatest();
        return $this->render('home.html.twig', [
            'films' => $lastFilms,
        ]);
    }
}