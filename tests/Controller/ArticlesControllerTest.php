<?php

namespace App\Test\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ArticlesRepository $repository;
    private string $path = '/articles/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Articles::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Article index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'article[title]' => 'Testing',
            'article[content]' => 'Testing',
            'article[createdAt]' => 'Testing',
            'article[updatedAt]' => 'Testing',
            'article[tags]' => 'Testing',
            'article[categories]' => 'Testing',
        ]);

        self::assertResponseRedirects('/articles/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Articles();
        $fixture->setTitle('My Title');
        $fixture->setContent('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setTags('My Title');
        $fixture->setCategories('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Article');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Articles();
        $fixture->setTitle('My Title');
        $fixture->setContent('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setTags('My Title');
        $fixture->setCategories('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'article[title]' => 'Something New',
            'article[content]' => 'Something New',
            'article[createdAt]' => 'Something New',
            'article[updatedAt]' => 'Something New',
            'article[tags]' => 'Something New',
            'article[categories]' => 'Something New',
        ]);

        self::assertResponseRedirects('/articles/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getContent());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getTags());
        self::assertSame('Something New', $fixture[0]->getCategories());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Articles();
        $fixture->setTitle('My Title');
        $fixture->setContent('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setTags('My Title');
        $fixture->setCategories('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/articles/');
    }
}
