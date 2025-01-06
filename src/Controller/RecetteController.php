<?php

// src/Controller/RecetteController.php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Recette;
use App\Form\CommentaireType;
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
        // Formulaire pour ajouter un commentaire
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setRecette($recette);
            $commentaire->setDate(new \DateTime());
            $em->persist($commentaire);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté !');
            return $this->redirectToRoute('recette_details', ['id' => $recette->getId()]);
        }

        return $this->render('recette/details.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/commentaire/{id}/editer', name: 'commentaire_editer')]
    public function editer(Commentaire $commentaire, Request $request, EntityManagerInterface $em): Response
    {
        // Vérifier que l'utilisateur a le droit d'éditer
        if ($this->getUser() !== $commentaire->getAuteur()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier ce commentaire.');
        }

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Votre commentaire a été modifié !');
            return $this->redirectToRoute('recette_details', ['id' => $commentaire->getRecette()->getId()]);
        }

        return $this->render('commentaire/editer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/commentaire/{id}/supprimer', name: 'commentaire_supprimer', methods: ['POST'])]
    public function supprimer(Commentaire $commentaire, Request $request, EntityManagerInterface $em): Response
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

