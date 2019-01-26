<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PolishNotationCalculatorController extends AbstractController
{
    /**
     * @Route("/polish/notation/calculator", name="polish_notation_calculator")
     */
    public function index()
    {
        return $this->render('polish_notation_calculator/index.html.twig', [
            'controller_name' => 'PolishNotationCalculatorController',
        ]);
    }
}
