<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactEntityTest extends KernelTestCase
{
    
    public function getEntity(): Contact
    {
        return (new Contact())
            ->setFullName('John Doe')
            ->setEmail('john@example.com')
            ->setSubject('Test Subject')
            ->setContent('Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.')
            ->setPhoneNumber('+1234567890')
            ->setRgpd(true)
            ->setSecurityQuestion('5')
            ->setIsView(false)
            ->setCreatedAt(new DateTimeImmutable('2023-08-24 12:00:00'));
    }
    private function assertHasErrors(Contact $code, int $number = 0)
    {
        // Fonction utilitaire pour vérifier les erreurs de validation
        self::bootKernel();
        $errors = static::getContainer()->get('validator')->validate($code);
        $messages = [];
        // @var ConstraintViolation $error 
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidationFullName()
    {
        // Test de validation pour le champ fullName
        $contact =  $this->getEntity();

        // Test de fullName valide
        $this->assertEquals('John Doe', $contact->getFullName());
        
        // Test de fullName vide
        $contact->setFullName('');
        $this->assertHasErrors($contact, 2); 

        // Test de fullName trop court
        $contact->setFullName('John');
        $this->assertHasErrors($contact, 1); 
    }

    public function testValidationEmail()
    {
        // Test de validation pour le champ email
        $contact = $this->getEntity();

        // Test d'email valide
        $this->assertEquals('john@example.com', $contact->getEmail());
        
        // Test d'email vide
        $contact->setEmail('');
        $this->assertHasErrors($contact, 2); 

        // Test d'email invalide
        $contact->setEmail('john@example');
        $this->assertHasErrors($contact, 1); 
    }

    public function testValidationSubject()
    {
        // Test de validation pour le champ subject
        $contact =  $this->getEntity();

        // Test de subject valide
        $this->assertEquals('Test Subject', $contact->getSubject());
        
        // Test de subject vide
        $contact->setSubject('');
        $this->assertHasErrors($contact, 2);

        // Test de subject trop court
        $contact->setSubject('test');
        $this->assertHasErrors($contact, 1);
    }

    public function testValidationContent()
    {
        // Test de validation pour le champ content
        $contact =  $this->getEntity();

        // Test de content valide
        $this->assertEquals('Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.', $contact->getContent());
        
        // Test de content vide
        $contact->setContent('');
        $this->assertHasErrors($contact, 2); 

        // Test de content trop court
        $contact->setContent('test content short');
        $this->assertHasErrors($contact, 1); 
    }

    public function testValidationPhoneNumber()
    {
        // Test de validation pour le champ phoneNumber
        $contact =  $this->getEntity();

        // Test de phoneNumber valide
        $this->assertEquals('+1234567890', $contact->getPhoneNumber());
        
        // Test de phoneNumber trop cours
        $contact->setPhoneNumber('123456789');
        $this->assertHasErrors($contact, 1); 

        // Test de phoneNumber trop long
        $contact->setPhoneNumber('123456789123456789123');
        $this->assertHasErrors($contact, 1); 

        // Test de phoneNumber invalide
        $contact->setPhoneNumber('123456789j');
        $this->assertHasErrors($contact, 1);
    }

    public function testValidationRGPD()
    {
    // Test de validation pour le champ rgpd
    $contact =  $this->getEntity();

    // Test de rgpd valide
    $this->assertTrue($contact->isRgpd());

    // Test de rgpd vide
    $contact->setRgpd(false);
    $this->assertHasErrors($contact, 1); 
}
    public function testValidationCreatedAt()
    {
        // Test de validation pour le champ createdAt
        $contact =  $this->getEntity();
        $date = new DateTimeImmutable('2023-08-24 12:00:00');
        // Test de createdAt valide
        $this->assertEquals($date, $contact->getCreatedAt());
    }

    public function testValidationHoneyPot()
    {
        // Test de validation pour le champ honeypot
        $contact =  $this->getEntity();

        // Test de honeypot valide
        $this->assertEmpty($contact->getHoneypot());

        $contact->setHoneypot('test');
        $this->assertEquals('test', $contact->getHoneypot());


    }
    public function testValidationSecurityQuestion()
    {
        // Test de validation pour le champ securityQuestion
        $contact =  $this->getEntity();

        // Test de securityQuestion valide
        $this->assertEquals('5', $contact->getSecurityQuestion());

    }
    public function testValidationIsView()
    {
        // Test de validation pour le champ Isview
        $contact =  $this->getEntity();

        // Test de Isview valide
        $this->assertEquals(false, $contact->isIsView());

    }

    public function testGetId()
{
    // Créez un objet Contact avec un ID spécifique (par exemple, 123)
    $contact = new Contact();
    $reflectionProperty = new \ReflectionProperty(Contact::class, 'id');
    $reflectionProperty->setAccessible(true);
    $reflectionProperty->setValue($contact, 123);

    // Utilisez la méthode getId() pour obtenir l'ID
    $id = $contact->getId();

    // Vérifiez si l'ID retourné est bien celui que vous avez défini
    $this->assertEquals(123, $id);
}
}