<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\PreRemove;

class AddressListener
{

	/*private $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}*/

	/**
	 * @PreRemove
	 *
	 * @param Address $address
	 * @param LifecycleEventArgs $args
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 * @throws \Exception
	 */
	public function PreRemove(Address $address, LifecycleEventArgs $args)
	{
		$a = 1;
		/** @var AddressRepository $re */
		$re = $args->getEntityManager()->getRepository(Address::class);
		if ($re->countByUser($address->getUser()) <= 1) {
			throw new \Exception('Remove denied');
		}
	}
}