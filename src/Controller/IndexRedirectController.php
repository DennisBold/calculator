<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexRedirectController extends AbstractController
{
    /**
     * @Route("/", name="index_redirect")
     */
    public function index()
    {
        return $this->render('index_redirect/index.html.twig', [
            'controller_name' => 'IndexRedirectController',
        ]);
    }
}
