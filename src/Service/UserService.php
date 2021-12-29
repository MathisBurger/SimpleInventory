<?php

namespace App\Service;

use App\Entity\PermissionGroups;
use App\Entity\Table;
use App\Entity\User;
use App\Exception\GroupNotFoundException;
use App\Exception\NotAuthorizedException;
use App\Repository\PermissionGroupsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserService
{

    private UserRepository $userRepository;
    private PermissionGroupsRepository $permissionGroupsRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepository $userRepository,
        Security $security,
        PermissionGroupsRepository $permissionGroupsRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->permissionGroupsRepository = $permissionGroupsRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Fetches all tables from the database that the user has access to.
     *
     * @return array All tables the user has access to
     * @throws NotAuthorizedException If the user is not authorized
     */
    public function getAllTablesForUser(): array
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new NotAuthorizedException('User is not authorized');
        }
        return array_map(function (PermissionGroups $permissionGroup) {
            return array_map(function(Table $table) {
                return $table;
            }, $permissionGroup->getTables()->getValues());
        }, $user->getPermissionGroups()->getValues());
    }

    /**
     * Creates a new user in the system and adds all requested permission groups to it
     *
     * @param string $username The username of the new user
     * @param string $password The password of the new user
     * @param array $permissionGroups The IDs of all permissionGroups the user should be added to
     * @return User The new user
     * @throws GroupNotFoundException If the permissionGroup the user should be added to do not exist
     * @throws NotAuthorizedException If the user is not authorized
     */
    public function createNewUser(string $username, string $password, array $permissionGroups): User
    {
        if (
            $this->security->isGranted(User::ROLE_MANAGER)
            || $this->security->isGranted(User::ROLE_ADMIN)
        ) {
            $usr = (new User())
                ->setUsername($username)
                ->setPassword($password);
            foreach ($permissionGroups as $permissionGroupId) {
                $permGroup = $this->permissionGroupsRepository->findOneBy(['id' => $permissionGroupId]);
                if ($permGroup === null) {
                    throw new GroupNotFoundException('The requested permission group was not found');
                }
                $permGroup->addUser($usr);
                $this->entityManager->persist($permGroup);
            }
            $this->entityManager->persist($usr);
            $this->entityManager->flush();
        } else {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
    }

}