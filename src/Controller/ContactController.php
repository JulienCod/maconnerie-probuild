<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        SendMailService $mail
    ): Response {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        $honeyPot = $form->getData()->getHoneypot();
        if ($honeyPot != null) {
            $this->addFlash('danger', 'Votre email n\'a pas été envoyer car il a était considéré comme spam');
        }

        if ($form->isSubmitted() && $form->isValid() && $honeyPot === null) {

            $contact = $form->getData();

            $entityManager->persist($contact);
            $entityManager->flush();

            // Utilisez la fonction strip_tags pour supprimer les balises HTML indésirables
            $sanitizedMessage = strip_tags($form->getData()->getContent());

            $mail->send(
                $form->getData()->getEmail(),
                'contact@maconnerie-probuild.fr',
                $form->getData()->getSubject(),
                'contact',
                [
                    "phoneNumber" => $form->getData()->getPhoneNumber(),
                    "fullName" => $form->getData()->getFullName(),
                    "message" => $sanitizedMessage,
                ]
            );

            $this->addFlash('success', 'Votre email a été envoyer');

            return $this->redirectToRoute('app_contact');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
