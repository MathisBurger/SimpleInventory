<?php

namespace App\Service;

use App\Entity\PermissionGroups;
use App\Exception\AlreadyContainsException;
use App\Exception\GroupNotFoundException;
use App\Exception\NotAuthorizedException;
use App\Exception\UserNotFoundException;
use App\Repository\PermissionGroupsRepository;
use App\Repository\UserRepository;
use App\Security\PermissionGroupVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PermissionGroupService
{
    private Security $security;
    private EntityManagerInterface $entityManager;
    private PermissionGroupsRepository $permissionGroupsRepository;
    private UserRepository $userRepository;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        PermissionGroupsRepository $permissionGroupsRepository,
        UserRepository $userRepository,
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->permissionGroupsRepository = $permissionGroupsRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Creates a new permission group in the inventory system.
     *
     * @param string $name The name of the permissionGroup
     * @param string $groupColor The color of the group as hexadecimal
     * @return PermissionGroups The new permission group
     * @throws NotAuthorizedException if the user has no permission
     */
    public function createPermissionGroup(string $name, string $groupColor): PermissionGroups
    {
        if (!$this->security->isGranted(PermissionGroupVoter::CREATE_GROUP)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        $group = (new PermissionGroups())
            ->setName($name)
            ->setGroupColor($groupColor);
        $this->entityManager->persist($group);
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

}