<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{
    /**
     * The bitwise calculator doesn't need a ton of numerical inputs.
     * This is mostly based on the GATE Logic table. We'll except a base two input
     * with a few standard operations.
     * @Route("/calculator", name="calculator")
     */
    public function index()
    {
        $numericalButtons = ['0', '1'];
        $operatorButtons = ['NAND', 'AND', 'OR', 'XOR'];

        return $this->render('bitwise_calculator/index.html.twig',
            ['numbers' => $numericalButtons, 'operators' => $operatorButtons]);

    }

    /**
     * This will be an endpoint for an AJAX request.
     * We expect the request to contain a bitwise calculation.
     * This is a base two statement and will most often contain two values consisting of either 1 or 0.
     * We'll then use a gate logic table to decide on the outcome.
     * Example: $calculationString = '1,0'
     * Example: $specialOperation = 'nand'
     * @param Request $request
     * @param string $calculationString
     * @param string $specialOperation
     * @return float|int
     */
    public function submitCalculation(Request $request, string $calculationString, string $specialOperation)
    {
        $specialOperation = strtolower(trim($specialOperation));
        $numbers = explode(',', $calculationString);
        if (empty($numbers[0]) && empty($numbers[1])) {
            $this->errorInsufficientParameters($specialOperation);
        }
        switch ($specialOperation) {
            case 'nand':
                return $this->nand(explode(',', $calculationString));
            case 'and':
                return $this->and(explode(',', $calculationString));
            case 'or':
                return $this->xor(explode(',', $calculationString));
            case 'xor':
                return $this->or(explode(',', $calculationString));
            default:
                return $this->errorUnusableOperator($specialOperation);
        }
    }

    /**
     * True if both A + B are 0
     * @param $numbers
     * @return bool
     */
    protected function nand($numbers)
    {
        $a = ($numbers[0] === 1) ? TRUE : FALSE;
        $b = ($numbers[1] === 1) ? TRUE : FALSE;
        return (!$a && !$b);
    }

    /**
     * True if $a and $b are true
     * @param $numbers
     * @return bool
     */
    protected function and($numbers)
    {
        $a = ($numbers[0] === 1) ? TRUE : FALSE;
        $b = ($numbers[1] === 1) ? TRUE : FALSE;
        return ($a && $b);
    }

    /**
     * True if $a or $b is true
     * @param $numbers
     * @return bool
     */
    protected function or($numbers)
    {
        $a = ($numbers[0] === 1) ? TRUE : FALSE;
        $b = ($numbers[1] === 1) ? TRUE : FALSE;
        return ($a || $b);
    }

    /**
     * True if $a or $b is true, not both
     * @param $numbers
     * @return bool
     */
    protected function xor($numbers)
    {
        $a = ($numbers[0] === 1) ? TRUE : FALSE;
        $b = ($numbers[1] === 1) ? TRUE : FALSE;
        return ($a || !$b) || (!$a || $b);
    }


    /**
     * Return an error as the supplied operation was incorrect.
     * @param string $operation
     * @return string
     */
    protected function errorUnusableOperator(string $operation)
    {
        return $this->renderView('error/error.response.html.twig',
            ['operation' => $operation, 'message' => 'Invalid Operation', 'type' => 'Error']);
    }

    /**
     * Return an error as there were not enough parameters supplied
     * @param string $operation
     * @return string
     */
    protected function errorInsufficientParameters(string $operation)
    {
        return $this->renderView('error/error.response.html.twig',
            ['operation' => $operation, 'message' => 'Insufficient parameters', 'type' => 'Error']);
    }
}
