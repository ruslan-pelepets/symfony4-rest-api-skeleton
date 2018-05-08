<?php

namespace App\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class ApiUserProvider implements UserProviderInterface
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadUserByUsername($username)
    {
        $userData =  $this->entityManager->getRepository(User::class)->findByUsernameOrEmail($username);

        if (null !== $userData) {
            $roles = $userData->getRoles();

            return $userData;
        }

        throw new UsernameNotFoundException(
           sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'App\Entity\User' === $class;
    }
}
