<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;

class TestController extends AbstractFOSRestController {

/**
 * Creates an test resource
 * @Rest\Get("/test")
 * @param Request $request
 * @return View
 */
public function test(Request $request): View
{
    $message = "this is a test";
    
    return View::create(["message" => $message], Response::HTTP_CREATED);
}

}