<?php

namespace App\Controller;

use App\Entity\CommentairesRecette; // Utiliser la bonne classe
use App\Entity\Recette;
use App\Form\CommentairesRecetteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetteController extends AbstractController
{
    #[Route('/recettes', name: 'recette_liste')]
    public function liste(EntityManagerInterface $em): Response
    {
        $recettes = $em->getRepository(Recette::class)->findAll();

        return $this->render('recette/liste.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    #[Route('/recette/{id}', name: 'recette_details')]
    public function details(Recette $recette, Request $request, EntityManagerInterface $em): Response
    {
        $ingredients = explode(',', $recette->getIngredients());
        $details = explode('.', $recette->getDetail());
    
        // Vérifier si on est en mode édition
        $commentaireId = $request->query->get('edit');
        $formEdit = null;
        $commentaireEnEdition = null;
    
        if ($commentaireId) {
            $commentaireEnEdition = $em->getRepository(CommentairesRecette::class)->find($commentaireId);
            
            // Vérifier que le commentaire existe et appartient à l'utilisateur courant
            if ($commentaireEnEdition && $commentaireEnEdition->getUser() === $this->getUser()) {
                $formEdit = $this->createForm(CommentairesRecetteType::class, $commentaireEnEdition);
                $formEdit->handleRequest($request);
    
                if ($formEdit->isSubmitted() && $formEdit->isValid()) {
                    // Pas besoin de persist() car l'entité est déjà managée
                    $em->flush();
                    $this->addFlash('success', 'Commentaire modifié avec succès !');
                    return $this->redirectToRoute('recette_details', ['id' => $recette->getId()]);
                }
            }
        }
    
        // Formulaire pour nouveau commentaire seulement si pas en mode édition
        $nouveauCommentaire = new CommentairesRecette();
        $formNouveau = $this->createForm(CommentairesRecetteType::class, $nouveauCommentaire);
        $formNouveau->handleRequest($request);
    
        if ($formNouveau->isSubmitted() && $formNouveau->isValid()) {
            $nouveauCommentaire->setRecette($recette);
            $nouveauCommentaire->setDateCommentaire(new \DateTime());
            $nouveauCommentaire->setUser($this->getUser());
            $em->persist($nouveauCommentaire);
            $em->flush();
    
            $this->addFlash('success', 'Commentaire ajouté avec succès !');
            return $this->redirectToRoute('recette_details', ['id' => $recette->getId()]);
        }
    
        return $this->render('recette/details.html.twig', [
            'recette' => $recette,
            'ingredients' => $ingredients,
            'details' => $details,
            'formNouveau' => $formNouveau->createView(),
            'formEdit' => $formEdit ? $formEdit->createView() : null,
            'commentaireEnEdition' => $commentaireId,
        ]);
    }    
    

  // La route pour éditer un commentaire
#[Route('/commentaire/{id}/editer', name: 'commentaire_edit')]
public function editer(CommentairesRecette $commentaire, Request $request, EntityManagerInterface $em): Response
{
    // Vérifier que l'utilisateur est bien celui qui a écrit le commentaire
    if ($this->getUser() !== $commentaire->getUser()) {
        throw $this->createAccessDeniedException('Vous ne pouvez pas modifier ce commentaire.');
    }

    // Créer le formulaire d'édition
    $form = $this->createForm(CommentairesRecetteType::class, $commentaire);
    $form->handleRequest($request);

    // Vérifier que le formulaire a été soumis et est valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Sauvegarder les modifications dans la base de données
        $em->flush();  // Cela va mettre à jour l'entité dans la base de données

        // Ajouter un message flash de succès
        $this->addFlash('success', 'Votre commentaire a été modifié !');

        // Rediriger vers la page des détails de la recette après l'édition
        return $this->redirectToRoute('recette_details', ['id' => $commentaire->getRecette()->getId()]);
    }

    // Retourner la vue d'édition avec le formulaire
    return $this->render('commentaire_recette/editer.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/commentaire/{id}/supprimer', name: 'commentaire_delete', methods: ['POST'])]
    public function supprimer(CommentairesRecette $commentaire, Request $request, EntityManagerInterface $em): Response
    {
        // Vérifier le CSRF token
        if ($this->isCsrfTokenValid('supprimer'.$commentaire->getId(), $request->request->get('_token'))) {
            $em->remove($commentaire);
            $em->flush();
            $this->addFlash('success', 'Le commentaire a été supprimé.');
        }

        return $this->redirectToRoute('recette_details', ['id' => $commentaire->getRecette()->getId()]);
    }
}
