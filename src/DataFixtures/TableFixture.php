<?php

namespace App\DataFixtures;

use App\Entity\Table;
use App\Entity\TableElement;
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
        $testElement = (new TableElement())
            ->setContent(['test' => 'test text']);

        $group = $manager->getRepository('App:PermissionGroups')->findOneBy(['name' => PermissionGroupFixtures::GROUP_NAME]);
        $table = (new Table())
            ->setTableName('Default table')
            ->addPermissionGroup($group)
            ->addTableElement($testElement);
    
        $manager->persist($testElement);
        $manager->persist($table);
        $manager->flush();
    }
}