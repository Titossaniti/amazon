<?php

namespace App\Controller;

use App\Form\FilterType;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Création du formulaire de filtre
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        // Récupération des articles selon les critères de filtre
        $criteria = $form->isSubmitted() && $form->isValid() ? $form->getData() : [];
        $articles = $articleRepository->findByCriteria($criteria);

        // Pagination des résultats
        $pagination = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );

        return $this->render('article/index.html.twig', [
            'articles' => $pagination,
            'filter_form' => $form->createView(),
        ]);
    }
}
