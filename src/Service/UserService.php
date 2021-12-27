<?php

namespace App\Service;

use App\Entity\PermissionGroups;
use App\Entity\Table;
use App\Entity\User;
use App\Exception\NotAuthorizedException;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class UserService
{

    private UserRepository $userRepository;
    private Security $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;

    }

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

}