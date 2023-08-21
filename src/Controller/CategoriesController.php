<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories', name:'admin_categories_')]
class CategoriesController extends AbstractController
{
    private $categoriesRepository;
    private $entityManager;
    public function __construct(
        CategoriesRepository $categoriesRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->categoriesRepository = $categoriesRepository;
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $this->categoriesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/categories/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Categories $category): Response
    {
        return $this->render('admin/categories/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categories $category): Response
    {
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/categories/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Categories $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
    }
}
