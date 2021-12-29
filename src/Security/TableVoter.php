<?php

namespace App\Security;

use App\Entity\Table;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TableVoter extends Voter
{
    public const CREATE_TABLE = 'CREATE_TABLE';
    public const DELETE_TABLE = 'DELETE_TABLE';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [
            self::CREATE_TABLE,
            self::DELETE_TABLE
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

        return match ($attribute) {
            self::CREATE_TABLE, self::DELETE_TABLE => $this->canCreateDeleteTable(),
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
}