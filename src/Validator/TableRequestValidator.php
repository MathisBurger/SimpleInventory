<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Service\Attribute\Required;

class TableRequestValidator extends ValidationHandler
{

    /**
     * Validates the createTable endpoint.
     */
    public function validateCreateTableRequest(Request $request): bool
    {
        $constraints = new Collection([
            'tableName' => new Type('string')
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the deleteTable endpoint.
     */
    public function validateDeleteTableRequest(Request $request): bool
    {
        $constraints = new Collection([
            'tableID' => new Type('integer')
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the addElement endpoint.
     */
    public function validateAddElementRequest(Request $request): bool
    {
        $constraints = new Collection([
            'tableID' => new Type('integer'),
            'content' => new Type('array')
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the removeElement endpoint.
     */
    public function validateRemoveElementRequest(Request $request): bool
    {
        $constraints = new Collection([
            'elementID' => new Type('integer')
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the updateElement endpoint.
     */
    public function validateUpdateElementRequest(Request $request): bool
    {
        $constraints = new Collection([
            'elementID' => new Type('integer'),
            'content' => new Type('array')
        ]);
        return $this->validateRequest($request, $constraints);
    }

    /**
     * Validates the getTable endpoint.
     */
    public function validateGetTableRequest(Request $request): bool
    {
        $constraints = new Collection([
            'tableID' => new Type('integer')
        ]);
        return $this->validateRequest($request, $constraints);
    }
}