<?php

namespace App\Controller;

use App\Exception\AlreadyContainsException;
use App\Exception\GroupNotFoundException;
use App\Exception\NotAuthorizedException;
use App\Exception\UserNotFoundException;
use App\Service\PermissionGroupService;
use App\Service\SerializingService;
use App\Validator\PermissionGroupRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * REST Controller for all permission group actions
 */
class PermissionGroupController extends DefaultResponsesWithAbstractController
{
    private PermissionGroupRequestValidator $validator;
    private PermissionGroupService $permissionGroupService;
    private SerializingService $serializingService;

    public function __construct(
        PermissionGroupRequestValidator $validator,
        PermissionGroupService $permissionGroupService,
        SerializingService $serializingService
    ) {
        $this->validator = $validator;
        $this->permissionGroupService = $permissionGroupService;
        $this->serializingService = $serializingService;
    }

    /**
     * Creates a new permission group in the inventory system
     */
    #[Route('/api/permission-group/createGroup', methods: Request::METHOD_POST)]
    public function createGroup(Request $request): Response
    {
        if (!$this->validator->validateCreateGroupRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $group = $this->permissionGroupService->createPermissionGroup(
                $requestContent['name'],
                $requestContent['groupColor'], 
                $requestContent['tables']
            );
            return $this->json([
                'message' => 'Successfully created new permission group',
                'group' => $this->serializingService->normalize($group),
            ]);
        } catch (NotAuthorizedException|ExceptionInterface $e) {
            return $this->notAuthorizedResponse();
        }
    }

    /**
     * Deletes the permission group from the server
     */
    #[Route('/api/permission-group/deleteGroup', methods: Request::METHOD_POST)]
    public function deleteGroup(Request $request): Response
    {
        if (!$this->validator->validateDeleteGroupRequest($request)) {
            return $this->notAuthorizedResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $this->permissionGroupService->deletePermissionGroup($requestContent['groupID']);
            return $this->json([
                'message' => 'Successfully deleted permission group'
            ]);
        } catch (GroupNotFoundException $e) {
            // Permission group not found on the server
            return $this->exceptionResponse($e->getMessage());
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        }
    }

    /**
     * Adds a user to a permission group.
     */
    #[Route('/api/permission-group/addUser', methods: Request::METHOD_POST)]
    public function addUser(Request $request): Response
    {
        if (!$this->validator->validateAddUserRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $this->permissionGroupService->addUserToPermissionGroup($requestContent['groupID'], $requestContent['userID']);
            return $this->json([
                'message' => 'User successfully added to permission group'
            ]);
        } catch (AlreadyContainsException|UserNotFoundException $e) {
            return $this->exceptionResponse($e->getMessage());
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        }
    }

    /**
     * Removes a user from a permission group
     */
    #[Route('/api/permission-group/removeUser', methods: Request::METHOD_POST)]
    public function removeUser(Request $request): Response
    {
        if (!$this->validator->validateRemoveUserRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $this->permissionGroupService->removeUserFromPermissionGroup($requestContent['groupID'], $requestContent['userID']);
            return $this->json([
                'message' => 'Successfully removed user from permission group'
            ]);
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        } catch (UserNotFoundException $e) {
            return $this->exceptionResponse($e->getMessage());
        }
    }

    /**
     * Adds a table to a permission group
     */
    #[Route('/api/permission-group/addTable', methods: Request::METHOD_POST)]
    public function addTable(Request $request): Response
    {
        if (!$this->validator->validateAddTableRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $this->permissionGroupService->addTableToPermissionGroup($requestContent['groupID'], $requestContent['tableID']);
            return $this->json([
                'message' => 'Successfully added table to permission group'
            ]);
        } catch (GroupNotFoundException $e) {
            return $this->exceptionResponse($e->getMessage());
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        }
    }

    /**
     * Removes a table from a permission group
     */
    #[Route('/api/permission-group/removeTable', methods: Request::METHOD_POST)]
    public function removeTable(Request $request): Response
    {
        if (!$this->validator->validateRemoveTableRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $this->permissionGroupService->removeTableFromPermissionGroup($requestContent['groupID'], $requestContent['tableID']);
            return $this->json([
              'message' => 'Successfully removed table from permission group'
            ]);
        } catch (GroupNotFoundException $e) {
            return $this->exceptionResponse($e->getMessage());
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        }
    }

    /**
     * Fetches all permission groups in the system.
     */
    #[Route('/api/permission-group/allGroups', methods: Request::METHOD_GET)]
    public function allGroups(): Response
    {
        try {
            return $this->json([
                'groups' => $this->serializingService->normalizeArray($this->permissionGroupService->getAllGroups())
            ]);
        } catch (NotAuthorizedException|ExceptionInterface $e) {
            return $this->notAuthorizedResponse();
        }
    }

}