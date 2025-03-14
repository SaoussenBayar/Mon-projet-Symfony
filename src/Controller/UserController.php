<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->userService->getAllUsers();
        return $this->render('user/index.html.twig', ['users' => $users]);
    }

    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $this->userService->createUser($request);
            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/new.html.twig');
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $this->userService->updateUser($user, $request);
            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/edit.html.twig', ['user' => $user]);
    }

    #[Route('/{id}/delete', name: 'user_delete', methods: ['POST'])]
    public function delete(User $user): Response
    {
        $this->userService->deleteUser($user);
        return $this->redirectToRoute('user_index');
    }
}
