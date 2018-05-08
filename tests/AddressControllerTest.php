<?php

namespace App\Tests;

use App\Entity\Address;
use GuzzleHttp\Exception\ClientException;

class AddressControllerTest extends BaseTest
{
	protected function setUp()
	{
		parent::setUp();
	}

	public function testNew()
	{
		$this->clearTable(Address::class);

		$re = $this->entityManager->getRepository(Address::class);
		$countQuery = $re->createQueryBuilder('a')
			->select('count(a.id)')
			->getQuery();
		$this->assertEquals(
			0,
			$countQuery->getSingleScalarResult()
		);

		$limit = 3;
		for ($i = 1; $i < $limit + 2; $i++)
		{
			try {
				$response = $this->client->post('/api/address', [
					'auth' => $this->auth,
					'json' => [
						'country' => 'Latvia',
						'city' => 'Riga',
						'zipCode' => 'LV1001',
						'street' => 'Brivibas str, 1',
					]
				]);
				$this->assertEquals(200, $response->getStatusCode());
			} catch (ClientException $e) {
				$this->assertTrue( $i > $limit);
				$this->assertEquals(400, $e->getCode());
			}
		}

		$this->assertEquals(
			$limit,
			$countQuery->getSingleScalarResult()
		);
	}

	public function testEdit()
	{
		$re = $this->entityManager->getRepository(Address::class);
		$addressQuery =  $re->createQueryBuilder('a')
			->setMaxResults(1)
			->getQuery();

		/** @var Address $address */
		$address = $addressQuery->getOneOrNullResult();
		$street = 'Brivibas str, ' . mt_rand(11111, 9999999999);

		$response = $this->client->put('/api/address/' . $address->getId(), [
			'auth' => $this->auth,
			'json' => [
				'street' => $street
			]
		]);
		$this->assertEquals(200, $response->getStatusCode());
		$this->entityManager->clear();

		$newAddress = $re->find($address->getId());
		$this->assertEquals($street, $newAddress->getStreet());
	}

	public function testItems()
	{
		$response = $this->client->get('/api/address/items', [
			'auth' => $this->auth,
		]);
		$this->assertEquals(200, $response->getStatusCode());

		$items = json_decode($response->getBody());
		$this->assertTrue(count($items) > 0);
	}

	public function testDelete()
	{
		$re = $this->entityManager->getRepository(Address::class);
		$addressQuery =  $re->createQueryBuilder('a')
			->setMaxResults(1)
			->getQuery();

		/** @var Address $address */
		$address = $addressQuery->getOneOrNullResult();

		try {
			$response = $this->client->delete('/api/address/' . $address->getId(), [
				'auth' => $this->auth,
			]);
			$this->assertEquals(200, $response->getStatusCode());
		}  catch (ClientException $e) {
			$this->assertEquals(403, $e->getCode());
		}
	}

	public function testGetOne()
	{
		$re = $this->entityManager->getRepository(Address::class);
		$addressQuery =  $re->createQueryBuilder('a')
			->setMaxResults(1)
			->getQuery();

		/** @var Address $address */
		$address = $addressQuery->getOneOrNullResult();

		$response = $this->client->get('/api/address/' . $address->getId(), [
			'auth' => $this->auth,
		]);
		$this->assertEquals(200, $response->getStatusCode());
	}
}