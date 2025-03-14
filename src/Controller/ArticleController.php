<?php
namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\ArticleService;
use App\Exception\ArticleNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
#[Route('/articles')]
class ArticleController extends AbstractController
{
    private ArticleService $articleService;
    private EntityManagerInterface $em;
    public function __construct(ArticleService $articleService, EntityManagerInterface $em)
    {
        $this->articleService = $articleService;
        $this->em = $em;
    }
    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Article $article, Request $request): Response
    {
        if (!$article) {
            throw new ArticleNotFoundException(); 
        }
        $success = $this->articleService->edit($article, $request);

        if ($success) {
            return $this->redirectToRoute('view_article');
        }
        return $this->render('article/edit.html.twig', [
            'article' => $article,
        ]);
    }
    #[Route('/', name: 'view_article', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {   
        $articles = $articleRepository->findAll();
        
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
