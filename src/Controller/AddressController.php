<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @Route("/api")
 */
class AddressController extends FOSRestController
{
	protected $user;

	protected function getUser()
	{
		if(!$this->user instanceof User) {
			/** @var TokenStorage $tokenStorage */
			$tokenStorage = $this->container->get('security.token_storage');
			$this->user = $tokenStorage->getToken()->getUser();
		}
		return $this->user;
	}

	/**
	 * @Rest\Post("/address")
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function newAction(Request $request)
	{
		// TODO: autowire deserializer
		$data = json_decode($request->getContent(), true);

		// TODO: move code to Service class
		$address = new Address();
		$address->setUser($this->getUser());
		$form = $this->createForm(AddressType::class, $address);
		$form->submit($data);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($address);
			$em->flush();

			$view = $this->view($address, 200);
			return $this->handleView($view);
		}

		$errors = $form->getErrors();
		/** @var FormError $e */
		$e = $errors->current();

		$view = $this->view(null, 400, ['x-error-message' => $e->getMessage()]);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Put("/address/{id}")
	 * @Rest\Route("/address/edit/{id}")
	 *
	 * @param Request $request
	 * @return null|\Symfony\Component\HttpFoundation\Response
	 */
	public function editAction(Request $request, int $id)
	{
		$data = json_decode($request->getContent(), true);

		$em = $this->getDoctrine()->getManager();
		$address = $em->getRepository(Address::class)->findOneBy([
			'id' => $id,
			'user' => $this->getUser()
		]);
		if(!$address instanceof Address) {
			$view = $this->view(null, 404);
			return $this->handleView($view);
		}

		$form = $this->createForm(AddressType::class, $address);
		$form->submit($data, false);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($address);
			$em->flush();

			$view = $this->view($address, 200);
			return $this->handleView($view);
		}

		$view = $this->view(null, 400);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/address/items")
	 */
	public function itemsAction()
	{
		$em = $this->getDoctrine()->getManager();
		/** @var AddressRepository $items */
		$items = $em->getRepository(Address::class)->findAllByUserPub($this->getUser());
			//->findBy(['user' => $this->getUser()]);

		if (!$items) {
			$view = $this->view(null, 204);
			return $this->handleView($view);
		}

		$view = $this->view($items, 200);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/address/first")
	 */
	public function firstAction()
	{
		$em = $this->getDoctrine()->getManager();
		$address = $em->getRepository(Address::class)->findDefaultPub(
			$this->getUser()
		);
		if(!$address) {
			$view = $this->view(null, 404);
			return $this->handleView($view);
		}

		$view = $this->view($address, 200);
		return $this->handleView($view);
	}

	/**
	 * @Rest\View
	 * @Rest\Get("/address/{id}", requirements={"id" = "\d+"}, defaults={"id" = 1})
	 * @param int $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function oneAction(int $id)
	{
		$em = $this->getDoctrine()->getManager();
		$address = $em->getRepository(Address::class)->findByUserIdPub(
			$id,
			$this->getUser()
		);
		if(!$address) {
			$view = $this->view(null, 404);
			return $this->handleView($view);
		}

		$view = $this->view($address, 200);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Delete("/address/{id}")
	 *
	 * @param Request $request
	 * @param int $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function deleteAction(Request $request, int $id)
	{
		$em = $this->getDoctrine()->getManager();
		$address = $em->getRepository(Address::class)->findOneBy([
			'id' => $id,
			'user' => $this->getUser()
		]);
		if(!$address instanceof Address) {
			$view = $this->view(null, 404);
			return $this->handleView($view);
		}

		try {
			$em->remove($address);
			$em->flush();
		} catch (\Exception $e) {
			$view = $this->view(null, 403);
			return $this->handleView($view);
		}

		$view = $this->view(null, 200);
		return $this->handleView($view);
	}
}