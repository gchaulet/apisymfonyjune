<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/default", name="default_index")
     */
    public function index()
    {
        return new JsonResponse([
            'action' => 'index',
            'path' => time(),
        ]);
    }

    //c14fb264414159f77539c17ffefc13a7
}
