<?php

namespace App\Tests;

use App\Tests\Handler\MockHttpHandler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class BaseTest extends WebTestCase
{
	/** @var Client */
	protected $client;

	protected $auth = ['ruslan', 'admin'];

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	protected function setUp()
	{
		$this->client = new Client(['handler' => HandlerStack::create(new MockHttpHandler())]);


		$kernel = self::bootKernel();

		$this->entityManager = $kernel->getContainer()
			->get('doctrine')
			->getManager();

		$encoder = $kernel->getContainer()
			->get('security.password_encoder');

		$this->clearTable(User::class);

		$user = new User();
		$user->setUsername('ruslan');
		$password = $encoder->encodePassword($user, 'admin');
		$user->setPassword($password);
		$user->setRoles(['ROLE_ADMIN']);
		$user->setFirstName('ruslan');
		$user->setLastName('pelepets');
		$user->setEmail('ruslan.pelepets@gmail.com');

		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}

	public function clearTable($className)
	{
		$cmd = $this->entityManager->getClassMetadata($className);
		$connection = $this->entityManager->getConnection();
		$dbPlatform = $connection->getDatabasePlatform();
		$connection->query('SET FOREIGN_KEY_CHECKS=0');
		$q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
		$result = $connection->executeUpdate($q);
		$connection->query('SET FOREIGN_KEY_CHECKS=1');

		return $result;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function tearDown()
	{
		parent::tearDown();

		$this->entityManager->close();
		$this->entityManager = null; // avoid memory leaks
	}
}