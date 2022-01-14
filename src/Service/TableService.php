<?php

namespace App\Service;

use App\Entity\Table;
use App\Entity\TableElement;
use App\Entity\User;
use App\Exception\NotAuthorizedException;
use App\Exception\TableNotFoundException;
use App\Repository\TableElementRepository;
use App\Repository\TableRepository;
use App\Security\TableVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TableService
{

    private Security $security;
    private TableRepository $tableRepository;
    private EntityManagerInterface $entityManager;
    private TableElementRepository $tableElementRepository;

    public function __construct(
        Security $security,
        TableRepository $tableRepository,
        EntityManagerInterface $entityManager,
        TableElementRepository $tableElementRepository
    ) {
        $this->security = $security;
        $this->tableRepository = $tableRepository;
        $this->entityManager = $entityManager;
        $this->tableElementRepository = $tableElementRepository;
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

    /**
     * Adds an element to the table with the given ID.
     *
     * @param int $tableID The ID of the table the element should be added to
     * @param mixed $content The json content of the element
     * @return Table The updated table
     * @throws NotAuthorizedException If the user is not authorized
     * @throws TableNotFoundException if the table does not exist in the system
     */
    public function addElement(int $tableID, mixed $content): Table
    {
        $table = $this->tableRepository->findOneBy(['id' => $tableID]);
        if ($table === null) {
            throw new TableNotFoundException('The table does not exist on this server');
        }
        if (!$this->security->isGranted(TableVoter::ADD_ELEMENT, $table)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $element = (new TableElement())
            ->setContent($content);
        $table->addTableElement($element);
        $this->entityManager->persist($table);
        $this->entityManager->persist($element);
        $this->entityManager->flush();
        return $table;
    }

    /**
     * Removes an element from the table with the given ID.
     *
     * @param int $elementID The ID of the element that should be removed
     * @return Table The updated table
     * @throws NotAuthorizedException If the user is not authorized
     * @throws TableNotFoundException If the table does not exist
     */
    public function removeElement(int $elementID): Table
    {
        $element = $this->tableElementRepository->findOneBy(['id' => $elementID]);
        if ($element === null) {
            throw new TableNotFoundException('The table element does not exist on this server');
        }
        if (!$this->security->isGranted(TableVoter::REMOVE_ELEMENT, $element->getParentTable())) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $element->getParentTable()->removeTableElement($element);
        $this->entityManager->remove($element);
        $this->entityManager->flush();
        return $element->getParentTable();
    }

    /**
     * Updates the content of the element with the provided ID.
     *
     * @param int $elementID The ID of the updated element
     * @param mixed $content The new content of the element
     * @return Table The updated table
     * @throws NotAuthorizedException If user is not authorized
     * @throws TableNotFoundException If the element does not exist
     */
    public function updateElement(int $elementID, mixed $content): Table
    {
        $element = $this->tableElementRepository->findOneBy(['id' => $elementID]);
        if ($element === null) {
            throw new TableNotFoundException('The table element does not exist on this server');
        }
        if (!$this->security->isGranted(TableVoter::REMOVE_ELEMENT, $element->getParentTable())) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $element->setContent($content);
        $this->entityManager->persist($element);
        $this->entityManager->flush();
        return $element->getParentTable();
    }

    /**
     * Returns all tables the user has access to.
     *
     * @return array All tables the user has access to
     * @throws NotAuthorizedException If the user is not authorized
     */
    public function getAllTablesForUser(): array
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        // If the user is an admin, he can see all tables
        if ($this->security->isGranted(User::ROLE_ADMIN)) {
            return $this->tableRepository->findAll();
        }
        return $this->tableRepository->findAllForUser($user);
    }

    /**
     * Gets the table with the provided ID.
     *
     * @param int $tableID The ID of the requested table
     * @return Table The requested table
     * @throws NotAuthorizedException If the user is not authorized
     * @throws TableNotFoundException If the table does not exist in the system.
     */
    public function getTable(int $tableID): Table
    {
        $table = $this->tableRepository->findOneBy(['id' => $tableID]);
        if (null === $table) {
            throw new TableNotFoundException('The requested table does not exist');
        }
        if (!$this->security->isGranted(TableVoter::VIEW_TABLE, $table)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        return $table;
    }

}