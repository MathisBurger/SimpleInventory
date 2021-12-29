<?php

namespace App\Service;

use App\Entity\PermissionGroups;
use App\Exception\GroupNotFoundException;
use App\Exception\NotAuthorizedException;
use App\Repository\PermissionGroupsRepository;
use App\Security\PermissionGroupVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PermissionGroupService
{

    private Security $security;
    private EntityManagerInterface $entityManager;
    private PermissionGroupsRepository $permissionGroupsRepository;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        PermissionGroupsRepository $permissionGroupsRepository
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->permissionGroupsRepository = $permissionGroupsRepository;
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

}