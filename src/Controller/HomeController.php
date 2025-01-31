<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleRepository; // Importation du repository pour accéder aux articles

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        // Récupérer tous les articles depuis la base de données
        $articles = $articleRepository->findAll();

        // Passer les articles à la vue
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articles, // Envoi des articles au template
        ]);
    }
}
