<?php

namespace App\Validator\Constraints;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AddressLimitValidator extends ConstraintValidator
{
	protected $limit = 3;
	private $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * Checks if the passed value is valid.
	 *
	 * @param Address $value The value that should be validated
	 * @param Constraint $constraint The constraint for the validation
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function validate($value, Constraint $constraint)
	{
		if($value->getId() === null) {
			/** @var AddressRepository $re */
			$re = $this->entityManager->getRepository(Address::class);
			if($re->countByUser($value->getUser()) >= $this->limit) {
				$this->context->buildViolation($constraint->message)
					->addViolation();
			}
		}
	}
}