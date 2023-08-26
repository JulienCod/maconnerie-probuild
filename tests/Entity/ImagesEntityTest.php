<?php

namespace App\Tests\Entity;

use App\Entity\Articles;
use App\Entity\Images;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ImagesEntityTest extends KernelTestCase
{
    public function getEntity(): Images
    {
        $article = new Articles();
        return (new Images())
            ->setName('name')
            ->setArticle($article);
    }
    private function assertHasErrors(Images $code, int $number = 0)
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
        $Images =  $this->getEntity();

        // Test de fullName valide
        $this->assertEquals('name', $Images->getName());

    }


    public function testGetId()
    {
        // Créez un objet Images avec un ID spécifique (par exemple, 123)
        $Images = new Images();
        $reflectionProperty = new \ReflectionProperty(Images::class, 'id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($Images, 123);

        // Utilisez la méthode getId() pour obtenir l'ID
        $id = $Images->getId();

        // Vérifiez si l'ID retourné est bien celui que vous avez défini
        $this->assertEquals(123, $id);
    }
}
