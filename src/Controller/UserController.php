<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\GroupNotFoundException;
use App\Exception\NotAuthorizedException;
use App\Exception\UserNotFoundException;
use App\Service\SerializingService;
use App\Service\UserService;
use App\Validator\UserRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * REST Controller for all user actions
 */
class UserController extends DefaultResponsesWithAbstractController
{
    private UserRequestValidator $validator;
    private UserService $userService;
    private SerializingService $serializingService;

    public function __construct(
        UserRequestValidator $userRequestValidator,
        UserService $userService,
        SerializingService $serializingService
    ) {
        $this->validator = $userRequestValidator;
        $this->userService = $userService;
        $this->serializingService = $serializingService;
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
               'user' => $this->normalizeUser($user)
            ]);
        } catch (GroupNotFoundException|ExceptionINterface $e) {
            // Permission group was not found in the database
            return $this->exceptionResponse($e->getMessage());
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
            return $this->json([
                'message' => 'Successfully removed user from system',
                'success' => true,
            ]);
        } catch (NotAuthorizedException $e) {
            return $this->notAuthorizedResponse();
        } catch (UserNotFoundException $e) {
            // Permission group was not found in the database
            return $this->exceptionResponse($e->getMessage());
        }
    }

    /**
     * Gets all users in the system
     */
    #[Route('/api/user/allUsers', methods: Request::METHOD_GET)]
    public function allUsers(): Response {
        try {
            return $this->json([
                'users' => array_map(function($user) {
                    return $this->normalizeUser($user);
                }, $this->userService->getAllUsers())
            ]);
        } catch (NotAuthorizedException|ExceptionInterface $e) {
            return $this->notAuthorizedResponse();
        }
    }

    /**
     * Normalizes a user and removes all non-exposable values.
     *
     * @param User $user The initial user
     * @return array The parsed array with deleted important data
     * @throws ExceptionInterface If the serialization failed
     */
    private function normalizeUser(User $user): array
    {
        $parsedUser = $this->serializingService->normalize($user);
        unset($parsedUser['password']);
        unset($parsedUser['token']);
        return $parsedUser;
    }
}