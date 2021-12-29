<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class TableRequestValidator extends ValidationHandler
{

    /**
     * Validates the createTable endpoint.
     */
    public function validateCreateTableRequest(Request $request): bool
    {
        $constraints = new Collection([
            'tableName' => new Required(new NotBlank())
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the deleteTable endpoint.
     */
    public function validateDeleteTableRequest(Request $request): bool
    {
        $constraints = new Collection([
            'tableID' => new Required(new NotBlank())
        ]);
        return $this->validateRequest($request, $constraints);
    }

}