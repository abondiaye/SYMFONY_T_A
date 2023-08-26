<?php

namespace App\Test\Controller;

use App\Entity\Beneficiaire;
use App\Repository\BeneficiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BeneficiaireControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private BeneficiaireRepository $repository;
    private string $path = '/beneficiaire/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Beneficiaire::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Beneficiaire index');

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
            'beneficiaire[type]' => 'Testing',
            'beneficiaire[besoin]' => 'Testing',
            'beneficiaire[description]' => 'Testing',
            'beneficiaire[source]' => 'Testing',
            'beneficiaire[articles]' => 'Testing',
        ]);

        self::assertResponseRedirects('/beneficiaire/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Beneficiaire();
        $fixture->setType('My Title');
        $fixture->setBesoin('My Title');
        $fixture->setDescription('My Title');
        $fixture->setSource('My Title');
        $fixture->setArticles('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Beneficiaire');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Beneficiaire();
        $fixture->setType('My Title');
        $fixture->setBesoin('My Title');
        $fixture->setDescription('My Title');
        $fixture->setSource('My Title');
        $fixture->setArticles('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'beneficiaire[type]' => 'Something New',
            'beneficiaire[besoin]' => 'Something New',
            'beneficiaire[description]' => 'Something New',
            'beneficiaire[source]' => 'Something New',
            'beneficiaire[articles]' => 'Something New',
        ]);

        self::assertResponseRedirects('/beneficiaire/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getBesoin());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getSource());
        self::assertSame('Something New', $fixture[0]->getArticles());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Beneficiaire();
        $fixture->setType('My Title');
        $fixture->setBesoin('My Title');
        $fixture->setDescription('My Title');
        $fixture->setSource('My Title');
        $fixture->setArticles('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/beneficiaire/');
    }
}
