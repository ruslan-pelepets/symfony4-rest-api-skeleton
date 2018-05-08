<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures extends Fixture implements ContainerAwareInterface
{
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
	}

	private function loadUsers(ObjectManager $manager)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');
        $userAdmin = new User();
        $userAdmin->setFirstName('ruslan');
        $userAdmin->setLastName('pelepets');
        $userAdmin->setUsername('ruslan');
        $userAdmin->setEmail('ruslan.pelepets@gmail.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $encodedPassword = $passwordEncoder->encodePassword($userAdmin, 'admin');
        $userAdmin->setPassword($encodedPassword);
        $manager->persist($userAdmin);
        $manager->flush();
    }
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
