<?php

namespace App\Security;

use App\Entity\PermissionGroups;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * General voter for actions all around the permission groups
 */
class PermissionGroupVoter extends Voter
{
    public const CREATE_GROUP = 'CREATE_GROUP';
    public const DELETE_GROUP = 'DELETE_GROUP';
    public const ADD_USER = 'ADD_USER';
    public const REMOVE_USER = 'REMOVE_USER';
    public const ADD_TABLE = 'ADD_TABLE';
    public const REMOVE_TABLE = 'REMOVE_TABLE';
    public const VIEW_GROUPS = 'VIEW_GROUPS';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [
            self::CREATE_GROUP,
            self::DELETE_GROUP,
            self::ADD_USER,
            self::REMOVE_USER,
            self::ADD_TABLE,
            self::REMOVE_TABLE,
            self::VIEW_GROUPS
        ])) {
            return false;
        }
        if ($subject !== null && !$subject instanceof PermissionGroups) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $loggedInUser = $this->security->getUser();

        if (!$loggedInUser instanceof User) {
            // User must be logged in
            return false;
        }

        return match ($attribute) {
            self::CREATE_GROUP,
            self::DELETE_GROUP,
            self::ADD_USER,
            self::REMOVE_USER,
            self::ADD_TABLE,
            self::REMOVE_TABLE => $this->canCreateOrDeleteGroup(),
            self::VIEW_GROUPS => $this->security->isGranted(User::ROLE_USER),
            default => false,
        };
    }

    /**
     * Checks if a user can create or delete a permission group
     *
     * @return bool if the user has the permission
     */
    private function canCreateOrDeleteGroup(): bool
    {
        return $this->security->isGranted(User::ROLE_MANAGER)
            || $this->security->isGranted(User::ROLE_ADMIN);
    }
}