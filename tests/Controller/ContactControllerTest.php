<?php

namespace App\Tests\Controller;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ContactControllerTest extends WebTestCase
{
private KernelBrowser $client;
private EntityManagerInterface $manager;
private ObjectRepository $repository;
private string $path = '/contact/';

protected function setUp(): void
{
$this->client = static::createClient();
$this->manager = static::getContainer()->get('doctrine')->getManager();
$this->repository = $this->manager->getRepository(Contact::class);

foreach ($this->repository->findAll() as $object) {
$this->manager->remove($object);
}

$this->manager->flush();
}

public function testIndex(): void
{
$crawler = $this->client->request('GET', $this->path);

self::assertResponseStatusCodeSame(200);
self::assertPageTitleContains('Contact index');

// Use the $crawler to perform additional assertions e.g.
// self::assertSame('Some text on the page', $crawler->filter('.p')->first());
}

public function testNew(): void
{
$this->client->request('GET', sprintf('%snew', $this->path));

self::assertResponseStatusCodeSame(200);

$this->client->submitForm('Save', [
'contact[firstname]' => 'Testing',
'contact[lastname]' => 'Testing',
]);

self::assertResponseRedirects($this->path);

self::assertSame(1, $this->repository->count([]));
}

public function testShow(): void
{
$fixture = new Contact();
$fixture->setFirstname('My Title');
$fixture->setLastname('My Title');

$this->manager->persist($fixture);
$this->manager->flush();

$this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

self::assertResponseStatusCodeSame(200);
self::assertPageTitleContains('Contact');

// Use assertions to check that the properties are properly displayed.
}

public function testEdit(): void
{
$fixture = new Contact();
$fixture->setFirstname('Value');
$fixture->setLastname('Value');

$this->manager->persist($fixture);
$this->manager->flush();

$this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

$this->client->submitForm('Update', [
'contact[firstname]' => 'Something New',
'contact[lastname]' => 'Something New',
]);

self::assertResponseRedirects('/contact/');

$fixture = $this->repository->findAll();

self::assertSame('Something New', $fixture[0]->getFirstname());
self::assertSame('Something New', $fixture[0]->getLastname());
}

public function testRemove(): void
{
$fixture = new Contact();
$fixture->setFirstname('Value');
$fixture->setLastname('Value');

$this->manager->persist($fixture);
$this->manager->flush();

$this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
$this->client->submitForm('Delete');

self::assertResponseRedirects('/contact/');
self::assertSame(0, $this->repository->count([]));
}
}