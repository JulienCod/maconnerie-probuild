<?php

namespace App\Controller;

use App\Form\FilterFormType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends AbstractController
{
    #[Route('/nos-réalisations', name: 'app_portfolio')]
    public function index(ArticlesRepository $articlesRepository, Request $request): Response
    {
        // Créer le formulaire de filtrage
        $form = $this->createForm(FilterFormType::class);
        $form->handleRequest($request);
        // Récupérer les articles en fonction des critères de filtrage
        $criteriaTags = [];
        $criteriaCategories = [];
       
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $tags = $data['tags'];
            foreach ($tags as $tag) {
                $criteriaTags[] = $tag->getId();
            }

            // Extraire les IDs des catégories sélectionnées
            $categories = $data['categories'];
            foreach ($categories as $category) {
                $criteriaCategories[] = $category->getId();
            }
        }

        if (empty($criteriaTags) && empty($criteriaCategories)) {
            $filteredArticles = $articlesRepository->findAll();
        } else {
            $filteredArticles = $articlesRepository->findByCombinedCriteria($criteriaTags, $criteriaCategories);
        }

        // Préparer les données pour l'affichage
        $articles = [];
        foreach ($filteredArticles as $dataArticle) {
            $article = [
                'title' => $dataArticle->getTitle(),
                'content' => $dataArticle->getContent(),
                'createdAt' => $dataArticle->getCreatedAt(),
                'images' => $dataArticle->getImages(),
            ];
            $tags = [];
            foreach ($dataArticle->getTags() as $tag) {
                $tags[] = [
                    'name' => $tag->getName(),
                ];
            }
            $article['tags'] = $tags;

            $categories = [];
            foreach ($dataArticle->getCategories() as $category) {
                $categories[] = [
                    'name' => $category->getName(),
                ];
            }
            $article['categories'] = $categories;

            $articles[] = $article;
        }

        return $this->render('pages/portfolio.html.twig', [
            'articles' => $articles,
            'filterForm' => $form->createView(), // Pass the form to the template
        ]);
    }

}
