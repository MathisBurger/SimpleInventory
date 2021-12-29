<?php

namespace App\Security;

use App\Entity\Table;
use App\Entity\User;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TableVoter extends Voter
{
    public const CREATE_TABLE = 'CREATE_TABLE';
    public const DELETE_TABLE = 'DELETE_TABLE';
    public const ADD_ELEMENT = 'ADD_ELEMENT';
    public const REMOVE_ELEMENT = 'REMOVE_ELEMENT';
    public const UPDATE_ELEMENT = 'UPDATE_ELEMENT';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [
            self::CREATE_TABLE,
            self::DELETE_TABLE,
            self::ADD_ELEMENT,
            self::REMOVE_ELEMENT,
            self::UPDATE_ELEMENT
        ])) {
            return false;
        }
        if ($subject !== null && !$subject instanceof Table) {
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

        /* @var $table Table */
        $table = $subject;

        return match ($attribute) {
            self::CREATE_TABLE, self::DELETE_TABLE => $this->canCreateDeleteTable(),
            self::ADD_ELEMENT, self::REMOVE_ELEMENT, self::UPDATE_ELEMENT => $this->canAddElement($loggedInUser, $table),
            default => false,
        };
    }

    /**
     * Checks if the user can create/delete tables.
     *
     * @return bool If the user can create/delete tables
     */
    private function canCreateDeleteTable(): bool
    {
        return $this->security->isGranted(User::ROLE_MANAGER)
            || $this->security->isGranted(User::ROLE_ADMIN);
    }

    /**
     * Checks if a user is allowed to add elements to a table.
     *
     * @param User $user The user that should be checked
     * @param Table $table The given table
     * @return bool If the user can add elements to permission group
     */
    #[Pure] private function canAddElement(User $user, Table $table): bool
    {
        $exists = false;
        foreach ($user->getPermissionGroups() as $permissionGroup) {
            foreach ($permissionGroup->getTables() as $permTable) {
                if ($permTable->getId() === $table->getId()) {
                    $exists = true;
                    break;
                }
            }
        }
        return $exists;
    }
}