<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AddressLimit extends Constraint
{
	public $message = 'Address limit is reached';

	public function validatedBy()
	{
		return get_class($this).'Validator';
	}

	public function getTargets()
	{
		return self::CLASS_CONSTRAINT;
	}
}