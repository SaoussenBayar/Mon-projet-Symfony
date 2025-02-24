<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleRepository; // Importation du repository pour accéder aux articles
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MongoDBService;
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository, MongoDBService $mongoDbService): Response
    {   $mongoDbService->insertVisit('app_home');

        // Récupérer tous les articles depuis la base de données
        $articles = $articleRepository->findAll();

        // Passer les articles à la vue
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articles, // Envoi des articles au template
        ]);
    }
        #[Route('/article/{id}', name: 'article_show')]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé.');
        }

        // Vérification si l'utilisateur est connecté
        if (!$this->getUser()) {
            // Ajouter un message flash d'erreur
            $this->addFlash('error', 'Oops ! Tu dois te connecter pour accéder au contenu des articles.');

            // Rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // Afficher l'article en entier
        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }

}
