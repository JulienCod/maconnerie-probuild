<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends AbstractController
{
    #[Route('/nos-réalisations', name: 'app_portfolio')]
    public function index(ArticlesRepository $articlesRepository): Response
    {
        $dataArticles = $articlesRepository->findAll();
        $articles = [];

        foreach ($dataArticles as $dataArticle) {
            // dd($dataArticle);
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

            $categories=[];
            foreach ($dataArticle->getCategories() as $category) {
                $categories[] = [
                    'name' => $category->getName(),
                ];
            }
            $article['categories'] = $categories;

            // dd($article);
            $articles[] =$article;
        }
        return $this->render('pages/portfolio.html.twig', compact('articles'));
    }
}
