<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response ;

class ContactControllerTest extends WebTestCase
{
    private $client;
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient([], ['REMOTE_ADDR' => '123.456.789.123']);
    }

    public function getForm()
    {
        $crawler = $this->client->request('GET', '/contact');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Nous contacter');

        $form = $crawler->selectButton('Envoyer')->form();
        $form['contact_form[email]'] = 'user@example.com';
        $form['contact_form[fullName]'] = 'test test';
        $form['contact_form[phoneNumber]'] = '0123456789';
        $form['contact_form[subject]'] = 'objet test';
        $form['contact_form[content]'] = 'Fusce a quam. Vestibulum fringilla pede sit amet augue. Curabitur vestibulum aliquam leo. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Nullam quis ante.';
        $form['contact_form[securityQuestion]'] = '5';
        $form['contact_form[rgpd]'] = true;

        return $form;
    }
    public function testEmailSending()
    {
        $form = $this->getForm();
        $this->client->submit($form);
        
        // Vérifiez la réponse HTTP et les messages flash
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        
        // Vérifiez l'envoi d'email simulé (utilisation de MailerAssertionsTrait)
        $this->assertEmailCount(1);

        $email = $this->getMailerMessage(0);
        $this->assertEmailHeaderSame($email, 'To', 'contact@maconnerie-probuild.fr');

        //redirige le client
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.flash-success', 'Votre email a été envoyé');
    }
    public function testEmailSendingHoneyPot()
    {
        $form = $this->getForm();
        $form['contact_form[honeypot]'] = 'test';
        $this->client->submit($form);
        
        // Vérifiez la réponse HTTP et les messages flash
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        
        //redirige le client
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.flash-alert', 'Votre email n\'a pas été envoyé car il a été considéré comme spam');
    }

    public function testSubmissionLimitReached()
    {
        // Simuler plusieurs soumissions pour atteindre la limite
        for ($i = 0; $i < 5; $i++) {
            $form = $this->getForm();
            $this->client->submit($form);
        }

        // Vérifiez que la réponse redirige et affiche le message flash d'avertissement
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.flash-warning', 'Vous avez atteint le nombre limite d\'envoi');
    }
    public function testisBan()
    {
        // Simuler plusieurs soumissions pour atteindre la limite
        for ($i = 0; $i < 3; $i++) {
            $crawler = $this->client->request('GET', '/contact');
            if ($i < 3)
            {
                $this->assertResponseIsSuccessful();
                $this->assertSelectorTextContains('h1', 'Nous contacter');
        
                $form = $crawler->selectButton('Envoyer')->form();
                $form['contact_form[email]'] = 'user@example.com';
                $form['contact_form[fullName]'] = 'test test';
                $form['contact_form[phoneNumber]'] = '0123456789';
                $form['contact_form[subject]'] = 'objet test';
                $form['contact_form[content]'] = 'Fusce a quam. Vestibulum fringilla pede sit amet augue. Curabitur vestibulum aliquam leo. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Nullam quis ante.';
                $form['contact_form[securityQuestion]'] = '5';
                $form['contact_form[rgpd]'] = true;
                $this->client->submit($form);
            }else{
                $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
                $this->client->followRedirect();
                $this->assertSelectorTextContains('div.flash-alert', 'Vous avez été banni vous ne pouvez plus utiliser les formulaires présent sur ce site');
            }
        }
    }
}
