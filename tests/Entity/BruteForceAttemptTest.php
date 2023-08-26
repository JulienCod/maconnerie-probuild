<?php

namespace App\Tests\Entity;

use App\Entity\BruteForceAttempt;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BruteForceAttemptTest extends KernelTestCase
{
    
    public function getEntity(): BruteForceAttempt
    {
        return (new BruteForceAttempt())
            ->setIp('123.456.789.120')
            ->setAttempts(1)
            ->setIsBan(false);
    }
    private function assertHasErrors(BruteForceAttempt $code, int $number = 0)
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

    public function testGetEntity()
    {
        // Test des getter  de l'entitée
        $BruteForceAttempt =  $this->getEntity();

        // Test de fullName valide
        $this->assertEquals('123.456.789.120', $BruteForceAttempt->getIp());
        $this->assertEquals(1, $BruteForceAttempt->getAttempts());
        $this->assertEquals(false, $BruteForceAttempt->isIsBan());
        
    }


    public function testGetId()
{
    // Créez un objet BruteForceAttempt avec un ID spécifique (par exemple, 123)
    $BruteForceAttempt = new BruteForceAttempt();
    $reflectionProperty = new \ReflectionProperty(BruteForceAttempt::class, 'id');
    $reflectionProperty->setAccessible(true);
    $reflectionProperty->setValue($BruteForceAttempt, 123);

    // Utilisez la méthode getId() pour obtenir l'ID
    $id = $BruteForceAttempt->getId();

    // Vérifiez si l'ID retourné est bien celui que vous avez défini
    $this->assertEquals(123, $id);
}
}