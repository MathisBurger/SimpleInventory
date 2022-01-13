<?php

namespace App\Service;

use App\Entity\PermissionGroups;
use App\Entity\Table;
use App\Entity\User;
use App\Exception\GroupNotFoundException;
use App\Exception\NotAuthorizedException;
use App\Exception\UserNotFoundException;
use App\Repository\PermissionGroupsRepository;
use App\Repository\UserRepository;
use App\Security\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class UserService
{

    private UserRepository $userRepository;
    private PermissionGroupsRepository $permissionGroupsRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $hasher;

    public function __construct(
        UserRepository $userRepository,
        Security $security,
        PermissionGroupsRepository $permissionGroupsRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher
    ) {
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->permissionGroupsRepository = $permissionGroupsRepository;
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
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
    public function createNewUser(string $username, string $password, array $permissionGroups, array $roles): User
    {
        if ($this->security->isGranted(UserVoter::CREATE_USER)) {
            $usr = (new User())
                ->setUsername($username);
            $usr->setPassword($this->hasher->hashPassword($usr, $password));
            foreach ($permissionGroups as $permissionGroupId) {
                $permGroup = $this->permissionGroupsRepository->findOneBy(['id' => $permissionGroupId]);
                if ($permGroup === null) {
                    throw new GroupNotFoundException('The requested permission group was not found');
                }
                $permGroup->addUser($usr);
                $this->entityManager->persist($permGroup);
            }
            if (in_array(User::ROLE_ADMIN, $roles) && !$this->security->isGranted(User::ROLE_ADMIN)) {
                throw new NotAuthorizedException('You are not authorized for this action');
            }
            foreach ($roles as $role) {
                $usr->addRole($role);
            }
            $this->entityManager->persist($usr);
            $this->entityManager->flush();
            return $usr;
        } else {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
    }

    /**
     * Deletes a user from the inventory system.
     *
     * @param int $userID The ID of the user that should be removed
     * @throws NotAuthorizedException If the user is not authorized to delete a user
     * @throws UserNotFoundException If the user that should be deleted does not exist
     */
    public function deleteUser(int $userID)
    {
        if ($this->security->isGranted(UserVoter::DELETE_USER)) {
            $user = $this->userRepository->findOneBy(['id' => $userID]);
            if ($user === null) {
                throw new UserNotFoundException('The user has not been found in the system');
            }
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        } else {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
    }

    /**
     * Returns all users in the system if the user is authorized.
     *
     * @return array All users in the system
     * @throws NotAuthorizedException If the user is not authorized
     */
    public function getAllUsers(): array
    {
        if (!$this->security->isGranted(UserVoter::VIEW_USERS)) {
            throw new NotAuthorizedException('You are not authorized for this action');
        }
        return $this->userRepository->findAll();
    }

}