<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserControllerTest extends WebTestCase
{
    private $client;
    private $em;

    protected function setUp(): void
    {
        $this->client = static::createClient(); // Crée un client pour simuler les requêtes HTTP
        $this->em = $this->client->getContainer()
            ->get('doctrine')
            ->getManager(); // Récupère le gestionnaire d'entités via le client
    }

    public function testIndex(): void
    {
        // Créer un utilisateur pour se connecter
        $user = new User();
        $user->setNom('Test User');
        $user->setEmail('testuser@example.com');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);
        $this->em->persist($user);
        $this->em->flush();

        // Connecter l'utilisateur
        $this->client->loginUser($user);

        // Tester la page index
        $this->client->request('GET', '/users/');

        $this->assertResponseIsSuccessful(); // Vérifie que la réponse est 200 OK
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs'); // Vérifie que le titre contient 'Liste des utilisateurs'
    }

    public function testNew(): void
    {
        // Créer un utilisateur pour se connecter
        $user = new User();
        $user->setNom('Test User');
        $user->setEmail('testuser@example.com');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);
        $this->em->persist($user);
        $this->em->flush();

        // Connecter l'utilisateur
        $this->client->loginUser($user);

        // Tester la page de création d'utilisateur
        $crawler = $this->client->request('GET', '/users/new');

        $this->assertResponseIsSuccessful(); // Vérifie que la page de création est accessible

        $form = $crawler->selectButton('Créer')->form([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'ROLE_USER'
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/users/'); // Vérifie la redirection après soumission

        // Vérifie que l'utilisateur est bien enregistré dans la base de données
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);
        $this->assertNotNull($user);
        $this->assertEquals('Test User', $user->getNom());
    }

    public function testEdit(): void
    {
        // Prépare un utilisateur pour le test
        $user = new User();
        $user->setNom('Old Name');
        $user->setEmail('old@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);
        $this->em->persist($user);
        $this->em->flush();

        // Connecter l'utilisateur (nécessaire si l'utilisateur est connecté pour la modification)
        $this->client->loginUser($user);

        // Tester la page de modification de l'utilisateur
        $crawler = $this->client->request('GET', '/users/' . $user->getId() . '/edit');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'name' => 'New Name',
            'email' => 'new@example.com',
            'role' => 'ROLE_ADMIN'
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/users/');

        $this->em->refresh($user);
        $this->assertEquals('New Name', $user->getNom());
        $this->assertEquals('new@example.com', $user->getEmail());
        $this->assertEquals(['ROLE_ADMIN'], $user->getRoles());
    }

    public function testDelete(): void
    {
        // Prépare un utilisateur pour le test
        $user = new User();
        $user->setNom('Delete Me');
        $user->setEmail('delete@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);
        $this->em->persist($user);
        $this->em->flush();

        // Connecter l'utilisateur
        $this->client->loginUser($user);

        // Tester la suppression de l'utilisateur
        $this->client->request('POST', '/users/' . $user->getId() . '/delete');

        $this->assertResponseRedirects('/users/');

        // Vérifie que l'utilisateur a été supprimé
        $deletedUser = $this->em->getRepository(User::class)->find($user->getId());
        $this->assertNull($deletedUser);
    }

    protected function tearDown(): void
    {
        // Supprime tous les utilisateurs ajoutés pendant les tests
        $users = $this->em->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $this->em->remove($user);
        }
        $this->em->flush();

        $this->em->close();
        parent::tearDown();
    }
}
