<?php

namespace App\Controller;

use App\Exception\GroupNotFoundException;
use App\Exception\NotAuthorizedException;
use App\Exception\UserNotFoundException;
use App\Service\UserService;
use App\Validator\UserRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends DefaultResponsesWithAbstractController
{
    private UserRequestValidator $validator;
    private UserService $userService;

    public function __construct(UserRequestValidator $userRequestValidator, UserService $userService)
    {
        $this->validator = $userRequestValidator;
        $this->userService = $userService;
    }

    /**
     * Creates a new user in the inventory system.
     */
    #[Route('/api/user/createUser', methods: Request::METHOD_POST)]
    public function createUser(Request $request): Response
    {
        if (!$this->validator->validateCreateUserRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);

        try {
            $user = $this->userService->createNewUser($requestContent['username'], $requestContent['password'], $requestContent['permissionGroups']);
            return $this->json([
               'message' => 'User created successfully',
               'user' => $user
            ]);
        } catch (GroupNotFoundException $e) {
            return $this->json([
                'message' => 'One of the requested permission groups was not found on the server'
            ], Response::HTTP_BAD_REQUEST);
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        }
    }

    /**
     * Deletes a user from the inventory system.
     */
    #[Route('/api/user/deleteUser', methods: Request::METHOD_POST)]
    public function deleteUser(Request $request): Response {

        if (!$this->validator->validateDeleteUserRequest($request)) {
            return $this->invalidRequestResponse();
        }
        $requestContent = json_decode($request->getContent(), true);
        try {
            $this->userService->deleteUser($requestContent['userID']);
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        } catch (UserNotFoundException $e) {
            return $this->json([
                'message' => 'The requested user cannot be found on the server'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}