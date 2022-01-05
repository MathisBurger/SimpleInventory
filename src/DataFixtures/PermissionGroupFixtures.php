<?php

namespace App\DataFixtures;

use App\Entity\PermissionGroups;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PermissionGroupFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {

        $group = (new PermissionGroups())
            ->setName('Default')
            ->setGroupColor('#fff');

        $manager->persist($group);
        $manager->flush();
    }
}