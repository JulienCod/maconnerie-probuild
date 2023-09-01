<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Service\BruteForceService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    private $cache;

    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        SendMailService $mail,
        BruteForceService $bruteForceService,
    ): Response {
        try {
            // Générez le nonce
            $nonce = bin2hex(random_bytes(16)); // Génère un nonce aléatoire de 128 bits (16 octets)
            //récupération de l'adresse Ip
            $ip = $request->getClientIp();
            // Vérification si l'adresse ip est banni
            $isBan = $bruteForceService->controlIsBan($ip);

            if ($isBan) {
                $this->addFlash('alert', 'Vous avez été banni vous ne pouvez plus utiliser les formulaires présent sur ce site');
                return $this->redirectToRoute('app_home');
            }

            $cacheKey = 'contact_form_submissions_' . $ip;
            $cacheItem = $this->cache->getItem($cacheKey);
            $submissions = $cacheItem->get() ?? 0;

            // définition des limitations 5 envoie pour 1 heure
            $submissionLimit = 5;
            $expirationTime = 3600; // 1 heure en secondes

            $contact = new Contact();
            $contact->setIsView(false);
            $form = $this->createForm(ContactFormType::class, $contact);
            $form->handleRequest($request);
            $honeyPot = $form->getData()->getHoneypot();

            if ($honeyPot != null) {
                $this->addFlash('alert', 'Votre email n\'a pas été envoyé car il a été considéré comme spam');
                return $this->redirectToRoute('app_contact');
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $cacheItem->set($submissions + 1);
                $cacheItem->expiresAfter($expirationTime);
                $this->cache->save($cacheItem);

                if ($submissions >= $submissionLimit) {
                    if($submissions >= $submissionLimit + 1 && !$isBan) {
                        $bruteForceService->logAttempt($ip);
                    }
                    $this->addFlash('warning', 'Vous avez atteint le nombre limite d\'envoi');
                    return $this->redirectToRoute('app_contact');
                }

                $contact = $form->getData();
                $entityManager->persist($contact);
                $entityManager->flush();
                // Décommenter les lignes suivantes pour la production
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

                $this->addFlash('success', 'Votre email a été envoyé');
                return $this->redirectToRoute('app_contact');
            }
            $response = new Response();
            $response->headers->set('Content-Security-Policy', "style-src 'self' 'nonce-$nonce'");

            return $this->render('pages/contact.html.twig', [
                'form' => $form->createView(),
                'nonce' => $nonce,
            ]);
        } catch (\Exception $e) {
            $this->addFlash('alert', 'Une erreur est survenue lors du traitement de votre demande');
            return $this->redirectToRoute('app_contact');
        }
    }
}
