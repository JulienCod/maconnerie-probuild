<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Images;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/articles', name:'app_articles_')]
class ArticlesController extends AbstractController
{
    private $pictureService;
    private $entityManager;
    private $articlesRepository;
    public function __construct(
        PictureService $pictureService,
        EntityManagerInterface  $entityManager,
        ArticlesRepository $articlesRepository,
    )
    {
        $this->pictureService = $pictureService;
        $this->entityManager = $entityManager;
        $this->articlesRepository = $articlesRepository;
    }
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $this->articlesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère les images
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                // on défibir ke dossier de destination
                $folder = 'articles';

                // on appelle le service d'ajout
                $fichier = $this->pictureService->add($image, $folder, 300, 300);

                $img = new Images();
                $img->setName($fichier);
                $article->addImage($img);

            }

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('admin/articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articles $article): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                // on défibir ke dossier de destination
                $folder = 'articles';

                // on appelle le service d'ajout
                $fichier = $this->pictureService->add($image, $folder, 300, 300);

                $img = new Images();
                $img->setName($fichier);
                $article->addImage($img);

            }
            $this->entityManager->flush();

            return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article): Response
    {

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $images = $article->getImages();
            //suppresion des images sur le serveur
            if(count($images) > 0) {
                foreach($images as $image) {
                    $nom = $image->getName();
                    if($this->pictureService->delete($nom, 'articles', 300, 300)) {
                        $this->entityManager->remove($image);
                    }
                }
            }
            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/suppression/image/{id}', name: 'delete_image', methods: ['POST'])]
    public function deleteImage(Request $request, Images $image): JsonResponse
    {

        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            // Le token csrf est valide
            $nom = $image->getName();
            if($this->pictureService->delete($nom, 'articles', 300, 300)) {
                $this->entityManager->remove($image);
                $this->entityManager->flush();

                $this->addFlash('success', "L'image a été supprimé avec succès");
                return new JsonResponse('Image supprimé avec succès', 200);
            }

            // erreur de suppression
            $this->addFlash('danger', "L'image n'a pas été supprimé suite a une erreur");
            return new JsonResponse('L\'image n\'a pas été supprimé suite a une erreur', 403);

        }
        $this->addFlash('danger', "Le token est invalide");
        return new JsonResponse('Le token est invalide', 403);
    }
}