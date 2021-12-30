<?php

namespace App\Repository;

use App\Entity\Table;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Table|null find($id, $lockMode = null, $lockVersion = null)
 * @method Table|null findOneBy(array $criteria, array $orderBy = null)
 * @method Table[]    findAll()
 * @method Table[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Table::class);
    }

    /**
     * @param User $user
     * @return array|Table[] All tables the user has access to
     */
    public function findAllForUser(User $user): array
    {
        return array_map(function ($group) {
            return array_map(function($table) {
                return $table;
            }, $group->getTables()->getValues());
        }, $user->getPermissionGroups()->getValues());
    }
}
