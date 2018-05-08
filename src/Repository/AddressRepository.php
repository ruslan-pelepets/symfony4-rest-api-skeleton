<?php

namespace App\Repository;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AddressRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
	{
		parent::__construct($registry, Address::class);
	}

	/**
	 * @param User $user
	 * @return int
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function countByUser(User $user)
	{
		return $this->createQueryBuilder('a')
			->select('count(a.id)')
			->where('a.user = :userId')
			->setParameter('userId', $user->getId())
			->getQuery()
			->getSingleScalarResult();
	}

	/**
	 * @param User $user
	 * @return Address[]
	 */
	public function findAllByUserPub(User $user)
	{
		return $this->createQueryBuilder('a')
			->select('a.id', 'a.city', 'a.country', 'a.street', 'a.zipCode')
			->where('a.user = :userId')
			->orderBy('a.id', 'ASC')
			->setParameter('userId', $user->getId())
			->getQuery()
			->getResult();
	}

	/**
	 * @param User $user
	 * @return Address
	 */
	public function findByUserIdPub(int $id, User $user)
	{
		return $this->createQueryBuilder('a')
			->select('a.id', 'a.city', 'a.country', 'a.street', 'a.zipCode')
			->where('a.id = :id  and a.user = :userId')
			->andWhere('a.user = :userId')
			->setParameter('id', $id)
			->setParameter('userId', $user->getId())
			->getQuery()
			->getOneOrNullResult();
	}
}
