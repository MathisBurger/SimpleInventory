<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Service\Attribute\Required;

class PermissionGroupRequestValidator extends ValidationHandler
{

    /**
     * Validates the createGroup endpoint.
     */
    public function validateCreateGroupRequest(Request $request): bool
    {
        $constraints = new Collection([
            'name' => new Type('string'),
            'groupColor' => new Type('string'),
            'tables' => new Type('array')
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the deleteGroup endpoint.
     */
    public function validateDeleteGroupRequest(Request $request): bool
    {
        $constraints = new Collection([
            'groupID' => new Type('integer')
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the addUser endpoint.
     */
    public function validateAddUserRequest(Request $request): bool
    {
        $constraints = new Collection([
            'groupID' => new Required(new NotBlank()),
            'userID' => new Required(new NotBlank())
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the removeUser endpoint.
     */
    public function validateRemoveUserRequest(Request $request): bool
    {
        $constraints = new Collection([
            'groupID' => new Required(new NotBlank()),
            'userID' => new Required(new NotBlank())
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the addTable endpoint.
     */
    public function validateAddTableRequest(Request $request): bool
    {
        $constraints = new Collection([
            'groupID' => new Required(new NotBlank()),
            'tableID' => new Required(new NotBlank())
        ]);
        return $this->validateRequest($request, $constraints);
    }

    public function validateRemoveTableRequest(Request $request): bool
    {
        $constraints = new Collection([
            'groupID' => new Required(new NotBlank()),
            'tableID' => new Required(new NotBlank())
        ]);
        return $this->validateRequest($request, $constraints);
    }
}