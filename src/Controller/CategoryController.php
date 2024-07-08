<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'category_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
    $categories = $categoryRepository->findAll();

    return $this->render('category/index.html.twig', [
    'categories' => $categories,
    ]);
    }

    #[Route('/categories/{id}', name: 'category_show')]
    public function show(int $id, CategoryRepository $categoryRepository, ArticleRepository $articleRepository): Response
    {
    $category = $categoryRepository->find($id);
    if (!$category) {
    throw $this->createNotFoundException('La catégorie n\'existe pas');
    }

    $articles = $articleRepository->findBy(['category' => $category]);

    return $this->render('category/show.html.twig', [
        'category' => $category,
        'articles' => $articles,
    ]);
    }
}
