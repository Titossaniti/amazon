<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CommercantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommercantController extends AbstractController
{
    #[Route('/commercants', name: 'commercant_index')]
    public function index(CommercantRepository $commercantRepository): Response
    {
        $commercants = $commercantRepository->findAll();

        return $this->render('commercant/index.html.twig', [
            'commercants' => $commercants,
        ]);
    }

    #[Route('/commercants/{id}', name: 'commercant_show')]
    public function show(int $id, CommercantRepository $commercantRepository, ArticleRepository $articleRepository): Response
    {
        $commercant = $commercantRepository->find($id);
        if (!$commercant) {
            throw $this->createNotFoundException('Le vendeur n\'existe pas');
        }

        $articles = $articleRepository->findBy(['commercant' => $commercant]);

        return $this->render('commercant/show.html.twig', [
            'commercant' => $commercant,
            'articles' => $articles,
        ]);
    }
}
