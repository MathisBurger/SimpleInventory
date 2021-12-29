<?php

namespace App\Service;

use App\Entity\Table;
use App\Exception\NotAuthorizedException;
use App\Exception\TableNotFoundException;
use App\Repository\TableRepository;
use App\Security\TableVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TableService
{

    private Security $security;
    private TableRepository $tableRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Security $security,
        TableRepository $tableRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->security = $security;
        $this->tableRepository = $tableRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a new table in the inventory system.
     *
     * @param string $tableName The name of the new table
     * @return Table The new table
     * @throws NotAuthorizedException If the user has no permission to access
     */
    public function createTable(string $tableName): Table
    {
        if (!$this->security->isGranted(TableVoter::CREATE_TABLE)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $table = (new Table())
            ->setTableName($tableName);
        $this->entityManager->persist($table);
        $this->entityManager->flush();
        return $table;
    }

    /**
     * Deletes a table from the inventory system.
     *
     * @param int $tableID The ID of the table that should be removed
     * @throws NotAuthorizedException If the user is not authorized
     * @throws TableNotFoundException If the requested table does not exist
     */
    public function deleteTable(int $tableID)
    {
        if (!$this->security->isGranted(TableVoter::DELETE_TABLE)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $table = $this->tableRepository->findOneBy(['id' => $tableID]);
        if ($table === null) {
            throw new TableNotFoundException('The requested table does not exist');
        }
        $this->entityManager->remove($table);
        $this->entityManager->flush();
    }

}