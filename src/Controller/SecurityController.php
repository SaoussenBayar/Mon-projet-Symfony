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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;  // Pour Symfony 5.3+
use Symfony\Component\HttpFoundation\JsonResponse;


class SecurityController extends AbstractController 
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function apiLogin(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        // Vérifiez que l'email et le mot de passe sont fournis
        if (empty($data['email']) || empty($data['password'])) {
            return $this->json(['message' => 'Email and password are required'], 400);
        }
    
        // Récupérez l'utilisateur depuis la base de données
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if (!$user) {
            return $this->json(['message' => 'Invalid credentials'], 401);
        }
        dump($user);
        // Vérifiez que le mot de passe est valide
        if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
            return $this->json(['message' => 'Invalid credentials'], 401);
        }
    
        // Génération du token JWT (vous pouvez utiliser LexikJWTAuthenticationBundle ou un autre outil)
        $token = 'votre_token_jwt'; // Remplacez ceci par votre logique de génération JWT.
    
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
