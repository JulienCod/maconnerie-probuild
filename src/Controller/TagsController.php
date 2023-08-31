<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Form\TagsType;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tags', name: 'admin_tags_')]
class TagsController extends AbstractController
{
    private $tagsRepository;
    private $entityManager;
    public function __construct(
        EntityManagerInterface $entityManager,
        TagsRepository $tagsRepository
        )
    {
        $this->tagsRepository = $tagsRepository;
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/tags/index.html.twig', [
            'tags' => $this->tagsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $tag = new Tags();
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($tag);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le tag a été créé');

            return $this->redirectToRoute('admin_tags_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/tags/new.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Tags $tag): Response
    {
        return $this->render('admin/tags/show.html.twig', [
            'tag' => $tag,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tags $tag): Response
    {
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Le tag a été modifié');

            return $this->redirectToRoute('admin_tags_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/tags/edit.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Tags $tag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($tag);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le tag a été supprimé');

            return $this->redirectToRoute('admin_tags_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('admin_tags_index', [], Response::HTTP_SEE_OTHER);
    }
}
