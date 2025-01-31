<?php

namespace App\Controller;

use App\Entity\CommentairesRecette; 
use App\Entity\Recette;
use App\Form\CommentairesRecetteType;
use App\Repository\CommentairesRecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class RecetteController extends AbstractController
{
    #[Route('/recettes', name: 'recette_liste')]
    public function liste(Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer les valeurs des paramètres de recherche et du filtre d'âge
        $search = $request->query->get('search', '');
        $age = $request->query->get('age', null);

        // Création du query builder pour la récupération des recettes
        $queryBuilder = $em->getRepository(Recette::class)->createQueryBuilder('r');

        // Recherche par titre (mot-clé)
        if (!empty($search)) {
            $queryBuilder->andWhere('r.titre LIKE :search')
                         ->setParameter('search', '%' . $search . '%');
        }

        // Filtrer par âge recommandé
        if ($age) {
            // Vérifier si l'âge est l'une des valeurs valides
            $queryBuilder->andWhere('r.age_recommende = :age')
                         ->setParameter('age', $age);
        }
        // Exécuter la requête et récupérer les résultats
        $recettes = $queryBuilder->getQuery()->getResult();

        // Retourner la réponse avec les résultats de recherche et de filtrage
        return $this->render('recette/liste.html.twig', [
            'recettes' => $recettes,
            'search' => $search,
            'age' => $age,
        ]);
    }



    #[Route('/recette/{id}', name: 'recette_details')]
    public function details(Recette $recette, Request $request, EntityManagerInterface $em): Response
    {
        if (!$recette) {
            throw $this->createNotFoundException('La recette n\'a pas été trouvée.');
        }

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
                    $em->flush();
                    $this->addFlash('success', 'Commentaire modifié avec succès !');
                    return $this->redirectToRoute('recette_details', ['id' => $recette->getId()]);
                }
            } else {
                throw $this->createAccessDeniedException('Vous ne pouvez pas modifier ce commentaire.');
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

    #[Route('/commentaire/{id}/editer', name: 'commentaire_edit')]
    public function editer(CommentairesRecette $commentaire, Request $request, EntityManagerInterface $em): Response
    {
        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire introuvable.');
        }

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
            $em->flush();

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
        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire introuvable.');
        }

        // Vérifier le CSRF token
        if ($this->isCsrfTokenValid('supprimer'.$commentaire->getId(), $request->request->get('_token'))) {
            if ($this->getUser() === $commentaire->getUser()) {
                $em->remove($commentaire);
                $em->flush();
                $this->addFlash('success', 'Le commentaire a été supprimé.');
            } else {
                throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer ce commentaire.');
            }
        }

        return $this->redirectToRoute('recette_details', ['id' => $commentaire->getRecette()->getId()]);
    }
       
    #[Route("/api/commentaires", name:"api_commentaires", methods: ['GET'])]
    
     
    public function getCommentaires(CommentairesRecetteRepository $commentaireRepo): JsonResponse
    {
        // Récupérer les commentaires non approuvés
        $commentaires = $commentaireRepo->findBy(['isApproved' => false]);

        // Sérialiser les données pour les envoyer sous forme JSON
        $commentaireData = array_map(function ($commentaire) {
            return [
                'id' => $commentaire->getId(),
                'texte' => $commentaire->getContenu(),
                'dateCommentaire' => $commentaire->getDateCommentaire()->format('Y-m-d H:i:s'),
                'utilisateur' => $commentaire->getUser()->getNom(),
                'isApproved' => $commentaire->getIsApproved(),
            ];
        }, $commentaires);

        return $this->json($commentaireData);
    }

   
    #[Route("/api/commentaire/{id}/approuver", name:"api_commentaire_approuver", methods: ['GET'])]
     
    public function approuverCommentaire(CommentairesRecette $commentaire, EntityManagerInterface $em): JsonResponse
    {
        $commentaire->setIsApproved(true);
        $em->flush();

        return $this->json(['message' => 'Commentaire approuvé avec succès.']);
    }

    
    #[Route("/api/commentaire/{id}/supprimer", name:"api_commentaire_supprimer", methods: ['GET'])]
     
    public function supprimerCommentaire(CommentairesRecette $commentaire, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($commentaire);
        $em->flush();

        return $this->json(['message' => 'Commentaire supprimé avec succès.']);
    }

}
