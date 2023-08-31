<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/contact', name: 'admin_contact_')]
class AdminContactController extends AbstractController
{
    private $entityManager;
    private $contactRepository;
    public function __construct(
        EntityManagerInterface $entityManager,
        ContactRepository $contactRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->contactRepository = $contactRepository;
    }
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $dataContacts = $this->entityManager->getRepository(Contact::class)->findAll();
        $contacts = [];

        foreach ($dataContacts as $contact){
            $contact = [
                "id" => $contact->getId(),
                "fullName" => $contact->getFullName(),
                "email" => $contact->getEmail(),
                "subject" => $contact->getSubject(),
                "content" => $contact->getContent(),
                "phoneNumber" => $contact->getPhoneNumber(),
                "createdAt" => $contact->getCreatedAt()->format('Y-m-d H:i:s'),
                "isView" => $contact->isIsView(),
            ];
            $contacts[] = $contact;
        }
        return $this->render('admin/contact/index.html.twig',compact('contacts'));
    }

    #[Route('/{id}', name: 'view', methods:['GET'])]
    public function view($id): Response
    {
        $data = $this->contactRepository->find($id);
        $data->setIsView(true);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        $contact = [
                "id" => $data->getId(),
                "fullName" => $data->getFullName(),
                "email" => $data->getEmail(),
                "subject" => $data->getSubject(),
                "content" => $data->getContent(),
                "phoneNumber" => $data->getPhoneNumber(),
                "createdAt" => $data->getCreatedAt()->format('Y-m-d H:i:s'),
                "isView" => $data->isIsView(),
            ];
        
        return $this->render('admin/contact/view.html.twig',compact('contact'));
    
    }
    #[Route('/{id}/supprimer', name: 'delete')]
    public function delete($id): Response
    {
        $contact = $this->contactRepository->find($id);
        
        if (!$contact){
            $this->addFlash('danger', 'le message n\'existe pas');
        }
        
        $this->entityManager->remove($contact);
        $this->entityManager->flush();
        
        
        $this->addFlash('success', 'le message a bien été supprimé');

        return $this->redirectToRoute('admin_contact_index');
    }
}
