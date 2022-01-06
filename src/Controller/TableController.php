<?php

namespace App\Controller;

use App\Exception\NotAuthorizedException;
use App\Exception\TableNotFoundException;
use App\Service\SerializingService;
use App\Service\TableService;
use App\Validator\TableRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * REST Controller for table management.
 */
class TableController extends DefaultResponsesWithAbstractController
{
    private TableRequestValidator $validator;
    private TableService $tableService;
    private SerializingService $serializingService;

    public function __construct(
        TableRequestValidator $validator,
        TableService $tableService,
        SerializingService $serializingService
    ) {
        $this->validator = $validator;
        $this->tableService = $tableService;
        $this->serializingService = $serializingService;
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
                'table' => $this->serializingService->normalize($table)
            ]);
        } catch (NotAuthorizedException|ExceptionInterface $e) {
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

    /**
     * Adds a new table element to the new table
     */
    #[Route('/api/table/addElement', methods: Request::METHOD_POST)]
    public function addElement(Request $request): Response
    {
        if (!$this->validator->validateAddElementRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $table = $this->tableService->addElement($requestContent['tableID'], $requestContent['content']);
            return $this->json([
                'message' => 'successfully added table element',
                'table' => $this->serializingService->normalize($table)
            ]);
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        } catch (TableNotFoundException|ExceptionInterface $e) {
            return $this->exceptionResponse($e->getMessage());
        }
    }

    /**
     * Removes an element from a table
     */
    #[Route('/api/table/removeElement', methods: Request::METHOD_POST)]
    public function removeElement(Request $request): Response
    {
        if (!$this->validator->validateRemoveElementRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $table = $this->tableService->removeElement($requestContent['elementID']);
            return $this->json([
                'message' => 'Successfully removed table element',
                'table' => $this->serializingService->normalize($table)
            ]);
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        } catch (TableNotFoundException|ExceptionInterface $e) {
            return $this->exceptionResponse($e->getMessage());
        }
    }

    /**
     * Updates a table element in a table
     */
    #[Route('/api/table/updateElement', methods: Request::METHOD_POST)]
    public function updateElement(Request $request): Response
    {
        if (!$this->validator->validateUpdateElementRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $table = $this->tableService->updateElement($requestContent['elementID'], $requestContent['content']);
            return $this->json([
                'message' => 'successfully updated table element',
                'table' => $this->serializingService->normalize($table)
            ]);
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        } catch (TableNotFoundException|ExceptionInterface $e) {
            return $this->exceptionResponse($e->getMessage());
        }
    }

    /**
     * Fetches all tables the user has access to
     */
    #[Route('/api/table/getAllTables', methods: Request::METHOD_GET)]
    public function getAllTablesForUser(): Response
    {
        try {
            return $this->json([
                'tables' => $this->serializingService->normalizeArray($this->tableService->getAllTablesForUser())
            ]);
        } catch (NotAuthorizedException|ExceptionInterface $e) {
            return $this->notAuthorizedResponse();
        }
    }

    /**
     * Gets the table with the provided ID.
     */
    #[Route('/api/table/getTable', methods: Request::METHOD_POST)]
    public function getTable(Request $request): Response
    {
        if (!$this->validator->validateGetTableRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            return $this->json([
                'table' => $this->serializingService->normalize($this->tableService->getTable($requestContent['tableID']))
            ]);
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        } catch (TableNotFoundException|ExceptionInterface $e) {
            return $this->exceptionResponse($e->getMessage());
        }
    }
}