<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Assigner les valeurs manquantes
            $user->setDateInscription(new \DateTime());
            $user->setRoles(['ROLE_USER']); // Assurer un rôle par défaut
            $user->setIsVerified(true);  // Marquer l'utilisateur comme vérifié (si applicable)
            
            // Encoder le mot de passe
            $user->setPassword(
                $passwordHasher->hashPassword(  // Utilise hashPassword
                    $user,
                    $form->get('password')->getData()
                )
            );
    
            // Sauvegarder l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'Inscription réussie ! Vous pouvez vous connecter.');
    
            // Redirection vers la page de connexion ou page d'accueil
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
