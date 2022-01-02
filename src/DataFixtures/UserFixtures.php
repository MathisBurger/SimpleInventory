<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * User fixtures for local testing and development
 */
class UserFixtures extends Fixture
{
    public const USER_MANAGER_USERNAME = 'manager';
    public const USER_MANAGER_PASSWORD = '123';

    public const USER_ADMIN_USERNAME = 'admin';
    public const USER_ADMIN_PASSWORD = '123';

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Loads all fixtures into the database
     */
    public function load(ObjectManager $manager): void
    {
        $managerUser = (new User())
            ->setUsername(self::USER_MANAGER_PASSWORD)
            ->addRole(User::ROLE_MANAGER);
        $managerUser->setPassword($this->hasher->hashPassword($managerUser, self::USER_MANAGER_PASSWORD));
        $adminUser = (new User())
            ->setUsername(self::USER_ADMIN_USERNAME)
            ->addRole(User::ROLE_ADMIN);
        $adminUser->setPassword($this->hasher->hashPassword($adminUser, self::USER_ADMIN_PASSWORD));

        $manager->persist($managerUser);
        $manager->persist($adminUser);
        $manager->flush();
    }
}
