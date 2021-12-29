<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultResponsesWithAbstractController extends AbstractController
{

    /**
     * A basic default response for invalid request bodys.
     *
     * @return Response The default response
     */
    protected function invalidRequestResponse(): Response
    {
        return $this->json([
            'message' => 'Your json request had the wrong shape',
            'code' => 400
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * A basic default response for an unauthorized user
     *
     * @return Response The default response
     */
    protected function notAuthorizedResponse(): Response
    {
        return $this->redirect('/login');
    }
}