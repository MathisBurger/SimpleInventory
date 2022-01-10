<?php

namespace App\DataFixtures;

use App\Entity\Table;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TableFixture extends Fixture implements DependentFixtureInterface {

    public function getDependencies(): array
    {
        return [
            PermissionGroupFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $group = $manager->getRepository('App:PermissionGroups')->findOneBy(['name' => PermissionGroupFixtures::GROUP_NAME]);
        $table = (new Table())
            ->setTableName('Default table')
            ->addPermissionGroup($group);
        $manager->persist($table);
        $manager->flush();
    }
}