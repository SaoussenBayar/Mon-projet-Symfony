<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils; 
use Symfony\Component\Routing\Annotation\Route; 
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class SecurityController extends AbstractController 
{
    #[Route('/login', name: 'app_login')] 
    public function login(AuthenticationUtils $authenticationUtils): Response 
    {
        $error = $authenticationUtils->getLastAuthenticationError(); 
        $lastUsername = $authenticationUtils->getLastUsername(); 

      // Vérifier si l'utilisateur est authentifié
        if ($this->getUser()) {
        // Rediriger vers la page d'accueil si l'utilisateur est déjà connecté
        return $this->redirectToRoute('app_home');
        }
        
        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')] 
    public function logout(): void {} 


}
