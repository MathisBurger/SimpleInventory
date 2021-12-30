<?php

namespace App\Repository;

use App\Entity\TableElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TableElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableElement[]    findAll()
 * @method TableElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableElement::class);
    }
}
