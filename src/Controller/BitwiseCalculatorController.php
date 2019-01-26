<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BitwiseCalculatorController extends AbstractController
{
    /**
     * @Route("/bitwise/calculator", name="bitwise_calculator")
     */
    public function index()
    {
        return $this->render('bitwise_calculator/index.html.twig', [
            'controller_name' => 'BitwiseCalculatorController',
        ]);
    }
}
