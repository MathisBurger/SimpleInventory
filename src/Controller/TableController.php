<?php

namespace App\Controller;

use App\Exception\NotAuthorizedException;
use App\Exception\TableNotFoundException;
use App\Service\TableService;
use App\Validator\TableRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * REST Controller for table management.
 */
class TableController extends DefaultResponsesWithAbstractController
{
    private TableRequestValidator $validator;
    private TableService $tableService;

    public function __construct(TableRequestValidator $validator, TableService $tableService)
    {
        $this->validator = $validator;
        $this->tableService = $tableService;
    }

    /**
     * Creates a table in the inventory system
     */
    #[Route('/api/table/createTable', methods: Request::METHOD_POST)]
    public function createTable(Request $request): Response {
        if (!$this->validator->validateCreateTableRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $table = $this->tableService->createTable($requestContent['tableName']);
            return $this->json([
                'message' => 'successfully created table',
                'table' => $table
            ]);
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        }
    }

    /**
     * Deletes a table in the inventory system.
     */
    #[Route('/api/table/deleteTable', methods: Request::METHOD_POST)]
    public function deleteTable(Request $request): Response {
        if (!$this->validator->validateDeleteTableRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $this->tableService->deleteTable($requestContent['tableID']);
            return $this->json([
                'message' => 'Successfully deleted table'
            ]);
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        } catch (TableNotFoundException $e) {
            return $this->exceptionResponse($e->getMessage());
        }
    }
}