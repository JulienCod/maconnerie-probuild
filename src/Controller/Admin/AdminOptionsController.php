<?php

namespace App\Controller\Admin;

use App\Form\OptionFormType;
use App\Repository\OptionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/options', name: 'admin_options_')]
class AdminOptionsController extends AbstractController
{
    private $entityManager;
    private $optionsRepository;
    public function __construct(
        EntityManagerInterface $entityManager,
        OptionsRepository $optionsRepository,
    )
    {
        $this->entityManager = $entityManager;
        $this->optionsRepository = $optionsRepository;
    }
    #[Route('/', name: 'index', methods:['GET', 'POST'])]
    public function index(Request $request): Response
    {

        $options = $this->optionsRepository->findAll();
        $forms = [];
    
        foreach ($options as $option) {
            $form = $this->createForm(OptionFormType::class, $option);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();
    
                $this->addFlash('success', 'Option mise à jour avec succès.');
            }
    
            $forms[$option->getId()] = $form->createView();
        }
    
        return $this->render('admin/options/index.html.twig', [
            'options' => $options,
            'forms' => $forms,
        ]);
    }

}
