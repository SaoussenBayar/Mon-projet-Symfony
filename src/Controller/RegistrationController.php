<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType; // Corrigé la faute de frappe ici
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
    
        // Création du formulaire
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        // Définir le rôle par défaut
        $user->setRoles(['ROLE_USER']);

        // Définit la date d'inscription à la date actuelle
        $user->setDateInscription(new \DateTime());
 
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // Hachage du mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($hashedPassword);
                
                // Enregistrement dans la base
                $entityManager->persist($user);
                $entityManager->flush();
    
                // Message de succès
                $this->addFlash('success', 'Utilisateur enregistré avec succès !');
                
                return $this->redirectToRoute('app_home');
            } else {
                // Si le formulaire n'est pas valide, on affiche un message d'erreur
                $this->addFlash('error', 'Il y a des erreurs dans votre formulaire. Veuillez les corriger.');
            }
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
