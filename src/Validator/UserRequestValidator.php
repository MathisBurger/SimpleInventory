<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class UserRequestValidator extends ValidationHandler
{

    /**
     * Validates the request for the createUser endpoint.
     */
    public function validateCreateUserRequest(Request $request): bool
    {
        $constraints = new Collection([
            'username' => new Required(new NotBlank()),
            'password' => new Required(new NotBlank()),
            'permissionGroups' => new Required(new NotBlank(), array())
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the request for deleteUser endpoint.
     */
    public function validateDeleteUserRequest(Request $request): bool
    {
        $constraints = new Collection([
            'userID' => new Required(new NotBlank())
        ]);
        return $this->validateRequest($request, $constraints);
    }

}