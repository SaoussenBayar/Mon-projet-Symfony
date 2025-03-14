<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function createUser(Request $request): void
    {
        $user = new User();
        $user->setPseudo($request->request->get('pseudo'));
        $user->setEmail($request->request->get('email'));
        
        // Hachage du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $request->request->get('password'));
        $user->setPassword($hashedPassword);
        
        $role = $request->request->get('role', 'ROLE_USER');
        $user->setRoles([$role]);
        $user->setDateInscription(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();
    }

    public function updateUser(User $user, Request $request): void
    {
        $user->setPseudo($request->request->get('pseudo'));
        $user->setEmail($request->request->get('email'));
        
        $role = $request->request->get('role', 'ROLE_USER');
        $user->setRoles([$role]);
        
        $this->em->flush();
    }

    public function deleteUser(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
