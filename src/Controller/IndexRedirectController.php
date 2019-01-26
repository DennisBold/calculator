<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexRedirectController extends AbstractController
{
    /**
     * A catch all. We have a route at /calculator
     * which will handle the type of calculator the person wants
     * to use. We could rewrite that to use "/" but we would rather
     * they go to "/calculator" as it's a bit more obvious as to what
     * this site is doing.
     * @Route("/", name="index_redirect")
     */
    public function index()
    {
        return new RedirectResponse($this->generateUrl('calculator'), '302');
    }
}
