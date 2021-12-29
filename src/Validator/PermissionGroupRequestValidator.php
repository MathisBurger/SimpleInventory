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
}