<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BitwiseCalculatorController extends AbstractController
{
    /**
     * The bitwise calculator doesn't need a ton of numerical inputs.
     * This is mostly based on the GATE Logic table. We'll except a base two input
     * with a few standard operations.
     * @Route("/bitwise/calculator", name="bitwise_calculator")
     */
    public function index()
    {
        $numericalButtons = ['0', '1'];
        $operatorButtons  = ['NAND', 'AND', 'OR', 'XOR'];

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
     * @Route("/arithmetic/calculator/submit", name="arithmetic_calculator_submit")
     * @param Request $request
     * @return float|int
     */
    public function submitCalculation(Request $request)
    {
        $calculationString = str_replace(' ', ',', preg_replace('!\s+!', ' ', trim($request->get('calculationString'))));
        $numbers           = explode(',', $calculationString);
        $specialOperation  = $this->getOperation($numbers);
        $numbers           = $this->getNumbers($numbers);
        if ($specialOperation != FALSE) {
            $argumentCheck = $this->checkArguments($numbers, $specialOperation);
            if ($argumentCheck !== TRUE) {
                switch ($specialOperation) {
                    case 'nand':
                        return !($this->and($numbers[0], $numbers[1]));
                    case 'and':
                        return $this->and($numbers[0], $numbers[1]);
                    case 'or':
                        return $this->xor($numbers[0], $numbers[1]);
                    case 'xor':
                        return $this->or($numbers[0], $numbers[1]);
                    default:
                        return $this->errorUnusableOperator($specialOperation);
                }
            } else {
                return $argumentCheck;
            }
        } else {
            return $this->errorUnusableOperator('');
        }
    }

    protected function checkArguments($numbers, $operator = '')
    {
        if (count($numbers) < 3 || count($numbers) > 3) {
            return $this->errorArguments($operator);
        }
        if (empty($numbers[0]) && empty($numbers[2])) {
            return $this->errorInsufficientParameters($operator);
        }
        return TRUE;
    }

    /**
     * True if both A + B are 0
     * @param $firstNumber
     * @param $secondNumber
     * @return bool
     */
    protected function and($firstNumber, $secondNumber)
    {
        $a = ($firstNumber === 1) ? TRUE : FALSE;
        $b = ($secondNumber === 1) ? TRUE : FALSE;
        return ($a && $b);
    }

    /**
     * True if $a or $b is true
     * @param $firstNumber
     * @param $secondNumber
     * @return bool
     */
    protected function or($firstNumber, $secondNumber)
    {
        $a = ($firstNumber === 1) ? TRUE : FALSE;
        $b = ($secondNumber === 1) ? TRUE : FALSE;
        return ($a || $b);
    }

    /**
     * True if $a or $b is true, not both
     * @param $firstNumber
     * @param $secondNumber
     * @return bool
     */
    protected function xor($firstNumber, $secondNumber)
    {
        $a = ($firstNumber === 1) ? TRUE : FALSE;
        $b = ($secondNumber === 1) ? TRUE : FALSE;
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

    /**
     * Return an error as there were not enough parameters supplied
     * @param string $operation
     * @return string
     */
    protected function errorArguments(string $operation)
    {
        return $this->renderView('error/error.response.html.twig',
            ['operation' => $operation, 'message' => 'Bitwise calculations can only be done against two numbers', 'type' => 'Error']);
    }

    protected function getOperation(array $numbers)
    {
        $operators = ['nand', 'and', 'or', 'xor'];
        foreach ($operators as $operator) {
            if (in_array(strtolower($operator), $numbers)) {
                return strtolower($operator);
            }
        }
        return FALSE;
    }

    protected function getNumbers($numbers)
    {
        $actualNumbers = [];
        foreach ($numbers as $number) {
            if (is_numeric($number)) {
                $actualNumbers[] = $numbers;
            }
        }
        return $actualNumbers;
    }
}
