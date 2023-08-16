<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Nous contacter');

        //récupérer le formulaire
        $submitButton = $crawler->selectButton('envoyer');
        $form = $submitButton->form();

        $form["contact_form[fullName]"] = "John Doe";
        $form["contact_form[email]"] = "john@doe.fr";
        $form["contact_form[phoneNumber]"] = "0601020304";
        $form["contact_form[subject]"] = "subject";
        $form["contact_form[content]"] = "Phasellus viverra nulla ut metus varius laoreet. Pellentesque egestas, neque sit amet convallis pulvinar, justo nulla eleifend augue, ac auctor orci leo non est. Praesent nonummy mi in odio. Morbi nec metus. Pellentesque posuere.

        Nulla neque dolor, sagittis eget, iaculis quis, molestie non, velit. Phasellus tempus. Etiam imperdiet imperdiet orci. Nullam accumsan lorem in dui. Donec mi odio, faucibus at, scelerisque quis, convallis in, nisi.";
        $form["contact_form[honeypot]"] = "";
        $form["contact_form[securityQuestion]"] = "5";
        $form["contact_form[rgpd]"] = true;

        //Soumettre le formulaire

        $client->submit($form);

        //Vérifier le statut HTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        //Vérifier l'envoie du mail
        $this->assertEmailCount(1);
        $client->followRedirect();
        //vérifier la présence du message de succès
    }
}
