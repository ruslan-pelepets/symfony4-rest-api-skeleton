<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as AppAssert;
use Doctrine\ORM\Mapping\EntityListeners;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 * @EntityListeners({"App\Entity\AddressListener"})
 * @ORM\Table(name="address")
 * @ORM\HasLifecycleCallbacks()
 *
 * @AppAssert\AddressLimit
 */
class Address
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="users")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $country;


	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $city;

	/**
	 * @ORM\Column(type="string", length=10)
	 */
	private $zipCode;

	/**
	 * @ORM\Column(type="string", length=256)
	 */
	private $street;

	/** @ORM\Column(type="datetime") */
	private $updated;

	public function __construct()
	{
		$this->updated= new \DateTime();
	}

	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate()
	{
		$this->updated = new \DateTime();
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getUpdated()
	{
		return $this->updated;
	}

	/**
	 * @return mixed
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user): void
	{
		$this->user = $user;
	}

	/**
	 * @return mixed
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * @param mixed $country
	 */
	public function setCountry($country): void
	{
		$this->country = $country;
	}

	/**
	 * @return mixed
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @param mixed $city
	 */
	public function setCity($city): void
	{
		$this->city = $city;
	}

	/**
	 * @return mixed
	 */
	public function getZipCode()
	{
		return $this->zipCode;
	}

	/**
	 * @param mixed $zipCode
	 */
	public function setZipCode($zipCode): void
	{
		$this->zipCode = $zipCode;
	}

	/**
	 * @return mixed
	 */
	public function getStreet()
	{
		return $this->street;
	}

	/**
	 * @param mixed $street
	 */
	public function setStreet($street): void
	{
		$this->street = $street;
	}
}