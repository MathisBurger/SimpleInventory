<?php

namespace App\DataFixtures;

use App\Entity\PermissionGroups;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PermissionGroupFixtures extends Fixture implements DependentFixtureInterface
{

    public const GROUP_NAME = 'Default';

    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository('App:User')->findOneBy(['username' => UserFixtures::USER_NAME]);

        $group = (new PermissionGroups())
            ->setName(self::GROUP_NAME)
            ->setGroupColor('#1794D7')
            ->addUser($user);

        $manager->persist($group);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}