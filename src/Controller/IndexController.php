<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Handles all index pages.
 */
class IndexController extends AbstractController
{
    /**
     * The default page controller
     */
    #[Route('/', methods: [Request::METHOD_GET])]
    public function index(): Response
    {
        return $this->json([
            'message' => 'this is an placeholder message'
        ], Response::HTTP_OK);
    }
}