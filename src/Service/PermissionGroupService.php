<?php

namespace App\Service;

use App\Entity\PermissionGroups;
use App\Exception\AlreadyContainsException;
use App\Exception\GroupNotFoundException;
use App\Exception\NotAuthorizedException;
use App\Exception\UserNotFoundException;
use App\Repository\PermissionGroupsRepository;
use App\Repository\TableRepository;
use App\Repository\UserRepository;
use App\Security\PermissionGroupVoter;
use App\Security\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PermissionGroupService
{
    private Security $security;
    private EntityManagerInterface $entityManager;
    private PermissionGroupsRepository $permissionGroupsRepository;
    private UserRepository $userRepository;
    private TableRepository $tableRepository;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        PermissionGroupsRepository $permissionGroupsRepository,
        UserRepository $userRepository,
        TableRepository $tableRepository
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->permissionGroupsRepository = $permissionGroupsRepository;
        $this->userRepository = $userRepository;
        $this->tableRepository = $tableRepository;
    }

    /**
     * Creates a new permission group in the inventory system.
     *
     * @param string $name The name of the permissionGroup
     * @param string $groupColor The color of the group as hexadecimal
     * @return PermissionGroups The new permission group
     * @throws NotAuthorizedException if the user has no permission
     */
    public function createPermissionGroup(string $name, string $groupColor, array $tables): PermissionGroups
    {
        if (!$this->security->isGranted(PermissionGroupVoter::CREATE_GROUP)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $group = (new PermissionGroups())
            ->setName($name)
            ->setGroupColor($groupColor);
        foreach ($tables as $tableID) {
            $table = $this->tableRepository->findOneBy(['id' => $tableID]);
            if (null === $table) {
                throw new NotAuthorizedException('You do not have access to this table');
            }
            $table->addPermissionGroup($group);
            $this->entityManager->persist($table);
            $this->entityManager->persist($group);
        }
        $this->entityManager->flush();
        return $group;
    }

    /**
     * Deletes a permission group from the inventory system.
     *
     * @param int $groupID The ID of the group that should be deleted
     * @throws GroupNotFoundException If the group was not found
     * @throws NotAuthorizedException If the user has not permission
     */
    public function deletePermissionGroup(int $groupID)
    {
        if (!$this->security->isGranted(PermissionGroupVoter::DELETE_GROUP)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $group = $this->permissionGroupsRepository->findOneBy(['id' => $groupID]);
        if ($group === null) {
            throw new GroupNotFoundException('The group does not exist in the system');
        }
        $this->entityManager->remove($group);
        $this->entityManager->flush();
    }

    /**
     * Adds the user with the provided ID to the permission group
     * with the provided ID.
     *
     * @param int $groupID The ID of the group
     * @param int $userID The ID of the user
     * @throws AlreadyContainsException If the user is already member of this group
     * @throws NotAuthorizedException If the user is not authorized for this action
     * @throws UserNotFoundException If the user or group does not exist in the database
     */
    public function addUserToPermissionGroup(int $groupID, int $userID)
    {
        if (!$this->security->isGranted(PermissionGroupVoter::ADD_USER)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $group = $this->permissionGroupsRepository->findOneBy(['id' => $groupID]);
        $user = $this->userRepository->findOneBy(['id' => $userID]);

        if ($group === null || $user === null) {
            throw new UserNotFoundException('The user or group are not existing on the server');
        }
        if ($user->getPermissionGroups()->contains($group)) {
            throw new AlreadyContainsException('The user is already in this group');
        }
        $group->addUser($user);
        $this->entityManager->persist($group);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Removes a user from the permission group
     *
     * @param int $groupID The ID of the group
     * @param int $userID The ID of the user
     * @throws NotAuthorizedException The user is not authorized for this action
     * @throws UserNotFoundException The user or group has not been found or the group does not contain the user
     */
    public function removeUserFromPermissionGroup(int $groupID, int $userID)
    {
        if (!$this->security->isGranted(PermissionGroupVoter::REMOVE_USER)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $group = $this->permissionGroupsRepository->findOneBy(['id' => $groupID]);
        $user = $this->userRepository->findOneBy(['id' => $userID]);

        if ($group === null || $user === null) {
            throw new UserNotFoundException('The user or group are not existing on the server');
        }
        if (!$user->getPermissionGroups()->contains($group)) {
            throw new UserNotFoundException('The user is not in this group');
        }
        $group->removeUser($user);
        $this->entityManager->persist($group);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Adds a table to a permission group.
     *
     * @param int $groupID The ID of the group
     * @param int $tableID The ID of the table
     * @throws GroupNotFoundException If the table or group does not exist
     * @throws NotAuthorizedException if the user is not authorized
     */
    public function addTableToPermissionGroup(int $groupID, int $tableID)
    {
        if (!$this->security->isGranted(PermissionGroupVoter::ADD_TABLE)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $group = $this->permissionGroupsRepository->findOneBy(['id' => $groupID]);
        $table = $this->tableRepository->findOneBy(['id' => $tableID]);
        if ($table === null || $group === null) {
            throw new GroupNotFoundException('The group or table does not exist');
        }
        $group->addTable($table);
        $this->entityManager->persist($group);
        $this->entityManager->persist($table);
        $this->entityManager->flush();
    }

    /**
     * Removes a table from a permission group.
     *
     * @param int $groupID The ID of the group
     * @param int $tableID The ID of the table
     * @throws GroupNotFoundException If the table or group does not exist
     * @throws NotAuthorizedException if the user is not authorized
     */
    public function removeTableFromPermissionGroup(int $groupID, int $tableID)
    {
        if (!$this->security->isGranted(PermissionGroupVoter::REMOVE_TABLE)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $group = $this->permissionGroupsRepository->findOneBy(['id' => $groupID]);
        $table = $this->tableRepository->findOneBy(['id' => $tableID]);
        if ($table === null || $group === null) {
            throw new GroupNotFoundException('The group or table does not exist');
        }
        $group->removeTable($table);
        $this->entityManager->persist($group);
        $this->entityManager->persist($table);
        $this->entityManager->flush();
    }

    /**
     * All permission groups in the system.
     *
     * @return array All permission groups in the system
     * @throws NotAuthorizedException If the user is not authorized
     */
    public function getAllGroups(): array
    {
        if (!$this->security->isGranted(PermissionGroupVoter::VIEW_GROUPS)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        return $this->permissionGroupsRepository->findAll();
    }

}