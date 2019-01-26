<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArithmeticCalculatorController extends AbstractController
{
    /**
     * @Route("/arithmetic/calculator", name="arithmetic_calculator")
     */
    public function index()
    {
        return $this->render('arithmetic_calculator/index.html.twig', [
            'controller_name' => 'ArithmeticCalculatorController',
        ]);
    }
}
