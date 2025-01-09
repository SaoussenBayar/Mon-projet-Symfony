<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType; 
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
    
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        $user->setRoles(['ROLE_USER']);

        $user->setDateInscription(new \DateTime());
 
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // Hachage du mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($hashedPassword);
                
                $entityManager->persist($user);
                $entityManager->flush();
    
                $this->addFlash('success', 'Utilisateur enregistré avec succès !');
                
                return $this->redirectToRoute('app_home');
            } else {
                $this->addFlash('error', 'Il y a des erreurs dans votre formulaire. Veuillez les corriger.');
            }
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
