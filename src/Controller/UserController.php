<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/commercants', name: 'commercant_index')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/commercants/{id}', name: 'commercant_show')]
    public function show(int $id, UserRepository $userRepository, ArticleRepository $articleRepository): Response
    {
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Le vendeur n\'existe pas');
        }

        $articles = $articleRepository->findBy(['user' => $user]);

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }
}
