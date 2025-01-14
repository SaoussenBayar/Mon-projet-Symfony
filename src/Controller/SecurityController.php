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
    
    #[Route('/reset-password', name: 'app_reset_password')]
    public function request(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if (!$user) {
                $this->addFlash('danger', 'Aucun utilisateur trouvé avec cet email.');
                return $this->redirectToRoute('app_reset_password');
            }

            // Générer un token
            $token = bin2hex(random_bytes(32));
            $user->setResetToken($token);
            $em->flush();

            // Envoyer un e-mail
            $resetUrl = $this->generateUrl('app_reset_password_token', ['token' => $token], true);

            $emailMessage = (new Email())
                ->from('projectcda36@gmail.com')
                ->to($user->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->text("Cliquez sur ce lien pour réinitialiser votre mot de passe : $resetUrl");

            $mailer->send($emailMessage);

            $this->addFlash('success', 'Un e-mail a été envoyé pour réinitialiser votre mot de passe.');
        }

        return $this->render('security/reset_password_request.html.twig');
    }

    // src/Controller/PasswordResetController.php

#[Route('/reset-password/{token}', name: 'app_reset_password_token')]
public function reset(string $token, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
{
    $user = $em->getRepository(User::class)->findOneBy(['resetToken' => $token]);

    if (!$user) {
        $this->addFlash('danger', 'Token invalide.');
        return $this->redirectToRoute('app_reset_password');
    }

    if ($request->isMethod('POST')) {
        $newPassword = $request->request->get('password');
        $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
        $user->setResetToken(null);
        $em->flush();

        $this->addFlash('success', 'Votre mot de passe a été réinitialisé.');
        return $this->redirectToRoute('app_login');
    }

    return $this->render('security/reset_password.html.twig', ['token' => $token]);
}


}
