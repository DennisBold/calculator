<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{
    /**
     * An empty page explaining calculators...
     * Because sometimes you need to explain your insanity.
     * @Route("/calculators", name="calculator")
     */
    public function index()
    {
        return $this->render('calculator/index.html.twig');
    }
}
