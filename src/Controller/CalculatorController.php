<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{
    /**
     * @Route("/calculator", name="calculator")
     */
    public function index()
    {
        $options = ['bitwise', 'arithmetic', 'polish'];
        return $this->render('calculator/index.html.twig', $options);
    }
}
