<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class PermissionGroupRequestValidator extends ValidationHandler
{

    /**
     * Validates the createGroup endpoint.
     */
    public function validateCreateGroupRequest(Request $request): bool
    {
        $constraints = new Collection([
            'name' => new Required(new NotBlank()),
            'groupColor' => new Required(new NotBlank())
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the deleteGroup endpoint.
     */
    public function validateDeleteGroupRequest(Request $request): bool
    {
        $constraints = new Collection([
            'groupID' => new Required(new NotBlank())
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