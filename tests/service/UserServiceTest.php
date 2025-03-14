<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\UserService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class UserServiceTest extends TestCase
{
    private $userRepository;
    private $entityManager;
    private $passwordHasher;
    private $userService;
    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $this->userService = new UserService($this->userRepository, $this->entityManager, $this->passwordHasher);
    }
    public function testCreateUser(): void
    {
        $request = new Request([], [
            'pseudo' => 'TestUser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'ROLE_USER',
        ]);

        $user = new User();
        $this->passwordHasher
            ->method('hashPassword')
            ->willReturn('hashed_password');
        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(User::class));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->userService->createUser($request);

        $this->assertEquals('TestUser', $request->request->get('pseudo'));
        $this->assertEquals('test@example.com', $request->request->get('email'));
        $this->assertEquals(['ROLE_USER'], [$request->request->get('role')]);
    }

    public function testUpdateUser(): void
    {
        $user = new User();
        $user->setPseudo('OldUser');
        $user->setEmail('old@example.com');
        $user->setRoles(['ROLE_USER']);

        $request = new Request([], [
            'pseudo' => 'UpdatedUser',
            'email' => 'updated@example.com',
            'role' => 'ROLE_ADMIN',
        ]);

        // Vérifie que `flush` est bien appelé
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->userService->updateUser($user, $request);

        $this->assertEquals('UpdatedUser', $user->getPseudo());
        $this->assertEquals('updated@example.com', $user->getEmail());
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    public function testDeleteUser(): void
    {
        $user = new User();

        // Vérifie que `remove` et `flush` sont bien appelés
        $this->entityManager
            ->expects($this->once())
            ->method('remove')
            ->with($user);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->userService->deleteUser($user);
    }
}
