<?php
namespace App\Controller;

use App\Form\ArticleType;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface; // On importe EntityManagerInterface qui est utilisé pour interagir avec la base de données
use Symfony\Component\HttpFoundation\Request; // Importation de la classe Request qui gère les données envoyées dans la requête HTTP
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/articles')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'view_article')]
    public function index(ArticleRepository $articleRepository): Response
    {   
        $articles = $articleRepository->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }
    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])] // La route '/new' pour afficher le formulaire de création et traiter l'envoi du formulaire
    public function new(Request $request, EntityManagerInterface $em): Response // La méthode new() gère l'affichage et la création de nouveaux articles
    {
        // Création d'un nouvel objet Article
        $article = new Article();
    
        // Création du formulaire basé sur l'entité Article
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request); // Traiter la requête (récupérer les données soumises par le formulaire)
    
        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
    
            // Récupérer l'image téléchargée
            $imageFile = $form->get('image')->getData();
            
            if ($imageFile) {
                // Dossier où l'image sera stockée
                $uploadsDirectory = $this->getParameter('kernel.project_dir') . 'assets/images';
        
                // Générer un nom unique pour l'image
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
    
                try {
                    // Déplacer le fichier téléchargé dans le dossier "uploads"
                    $imageFile->move($uploadsDirectory, $newFilename);
        
                    // Enregistrer le nom de l'image dans l'entité Article
                    $article->setImage($newFilename);
                } catch (\Exception $e) {
                    // Ajouter un message d'erreur si l'upload échoue
                    $this->addFlash('error', 'Failed to upload image.');
                }
            }
            
            // Enregistrer l'article dans la base de données
            $em->persist($article);
            $em->flush();
    
            // Ajouter un message flash de succès
            $this->addFlash('success', 'Article created successfully!');
            
            // Rediriger vers la page de l'article nouvellement créé (view_article)
            return $this->redirectToRoute('view_article', ['id' => $article->getId()]);
        }
    
        // Si la méthode est GET (formulaire de création), on affiche le formulaire
        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            // Récupère et met à jour les informations de l'article
            $article->setTitre($request->request->get('titre'));
            $article->setContenu($request->request->get('contenu'));
    
            // Gérer l'upload de l'image uniquement si une nouvelle image est soumise
            $uploadedFile = $request->files->get('image');
            
            if ($uploadedFile) {
                // Gérer l'upload du fichier
                $uploadsDirectory = $this->getParameter('uploads_directory'); // Dossier des uploads
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
    
                // Déplacer le fichier vers le dossier de téléchargement
                $uploadedFile->move($uploadsDirectory, $newFilename);
    
                // Mettre à jour le chemin de l'image dans l'article
                $article->setImage($newFilename);
            } else {
                // Conserver l'ancienne image si aucune nouvelle image n'est téléchargée
                $article->setImage($article->getImage()); // Cela évite de perdre l'ancienne image
            }
    
            // Sauvegarde les modifications apportées dans la base de données
            $em->flush();
    
            // Redirige vers la page de la liste des articles après modification
            return $this->redirectToRoute('view_article');
        }
    
        // Affiche le formulaire avec les données de l'article à modifier
        return $this->render('article/edit.html.twig', ['article' => $article]);
    }
    
    #[Route('/{id}/delete', name: 'article_delete', methods: ['POST'])] // La route '/{id}/delete' permet de supprimer un utilisateur
    public function delete(Article $article, EntityManagerInterface $em): Response // La méthode delete() permet de supprimer un utilisateur existant
    {
        $em->remove($article); // Supprime l'utilisateur de la base de données
        $em->flush(); // Sauvegarde la suppression dans la base de données

        return $this->redirectToRoute('view_article'); // Redirige vers la liste des utilisateurs après suppression
    }

}


