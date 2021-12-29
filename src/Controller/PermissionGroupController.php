<?php

namespace App\Controller;

use App\Exception\GroupNotFoundException;
use App\Exception\NotAuthorizedException;
use App\Service\PermissionGroupService;
use App\Validator\PermissionGroupRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * REST Controller for all permission group actions
 */
class PermissionGroupController extends DefaultResponsesWithAbstractController
{
    private PermissionGroupRequestValidator $validator;
    private PermissionGroupService $permissionGroupService;

    public function __construct(
        PermissionGroupRequestValidator $validator,
        PermissionGroupService $permissionGroupService
    ) {
        $this->validator = $validator;
        $this->permissionGroupService = $permissionGroupService;
    }

    /**
     * Creates a new permission group in the inventory system
     */
    #[Route('/api/permission-group/createGroup', methods: Request::METHOD_POST)]
    public function createPermissionGroup(Request $request): Response
    {
        if (!$this->validator->validateCreateGroupRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $group = $this->permissionGroupService->createPermissionGroup($requestContent['name'], $requestContent['groupColor']);
            return $this->json([
                'message' => 'Successfully created new permission group',
                'group' => $group
            ]);
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        }
    }

    /**
     * Deletes the permission group from the server
     */
    #[Route('/api/permission-group/deleteGroup', methods: Request::METHOD_POST)]
    public function deletePermissionGroup(Request $request): Response
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
            return $this->elementNotFoundResponse('permission group');
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        }
    }



}