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
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function apiLogin(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Valider que l'email et le mot de passe sont présents
        if (empty($data['email']) || empty($data['password'])) {
            return $this->json(['message' => 'Email and password are required'], 400);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($data['email']);
        if (!$user || !$this->encoder->isPasswordValid($user, $data['password'])) {
            return $this->json(['message' => 'Invalid credentials'], 401);
        }

        // Générer le JWT
        $token = $this->jwtManager->create($user);

        return $this->json(['token' => $token]);
    }

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
