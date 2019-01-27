<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Mixed_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/bitwise/calculator/submit", name="bitwise_calculator_submit")
     * @param Request $request
     * @return JsonResponse|string
     */
    public function submitCalculation(Request $request)
    {
        $calculationString = str_replace(' ', ',', preg_replace('!\s+!', ' ', trim($request->get('calculationString'))));
        $numbers           = explode(',', $calculationString);
        $specialOperation  = $this->getOperation($numbers);
        $numbers           = $this->getNumbers($numbers);
        if ($specialOperation != FALSE) {
            $argumentCheck = $this->checkArguments($numbers, $specialOperation);
            if ($argumentCheck == TRUE) {
                switch ($specialOperation) {
                    case 'and':
                        $outcome = $this->and($numbers[0], $numbers[1]);
                        break;
                    case 'nand':
                        $outcome = $this->nand($numbers[0], $numbers[1]);
                        break;
                    case 'or':
                        $outcome = $this->or($numbers[0], $numbers[1]);
                        break;
                    case 'xor':
                        $outcome = $this->xor($numbers[0], $numbers[1]);
                        break;
                    default:
                        return new JsonResponse('Dennis3' . $this->errorUnusableOperator($specialOperation));
                }
            } else {
                return new JsonResponse(' Dennis' . $argumentCheck);
            }
        } else {
            return new JsonResponse('Dennis 2 ' . $this->errorUnusableOperator(''));
        }
        $outcome = ($outcome == TRUE) ? ' TRUE' : ' FALSE';
        return new JsonResponse($outcome);
    }

    protected function checkArguments($numbers, $operator = '')
    {
        if (count($numbers) < 2 || count($numbers) > 2) {
            return $this->errorArguments($operator);
        }
        return TRUE;
    }

    /**
     * True if both A + B are different
     * @param $firstNumber
     * @param $secondNumber
     * @return bool
     */
    protected function nand($firstNumber, $secondNumber)
    {
        $a = ($firstNumber == '1') ? TRUE : FALSE;
        $b = ($secondNumber == '1') ? TRUE : FALSE;
        if ($a && $b) {
            return FALSE;
        } elseif (!$a && !$b) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * True if both A + B are 0
     * @param $firstNumber
     * @param $secondNumber
     * @return bool
     */
    protected function and($firstNumber, $secondNumber)
    {
        $a = ($firstNumber == '1') ? TRUE : FALSE;
        $b = ($secondNumber == '1') ? TRUE : FALSE;
        if ($a && $b) {
            return TRUE;
        } elseif (!$a && !$b) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * True if $a or $b is true
     * @param $firstNumber
     * @param $secondNumber
     * @return bool
     */
    protected function or($firstNumber, $secondNumber)
    {
        $a = ($firstNumber == '1') ? TRUE : FALSE;
        $b = ($secondNumber == '1') ? TRUE : FALSE;
        if ($a && !$b) {
            return TRUE;
        } elseif (!$a && $b) {
            return TRUE;
        } elseif ($a && $b) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * True if $a or $b is true, not both
     * @param $firstNumber
     * @param $secondNumber
     * @return bool
     */
    protected function xor($firstNumber, $secondNumber)
    {
        $a = ($firstNumber == '1') ? TRUE : FALSE;
        $b = ($secondNumber == '1') ? TRUE : FALSE;
        if (!$a && !$b) {
            return TRUE;
        } elseif (!$a && !$b) {
            return TRUE;
        } else {
            return FALSE;
        }
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
        $operators = ['NAND', 'AND', 'OR', 'XOR'];
        foreach ($operators as $operator) {
            if (in_array(trim($operator), $numbers)) {
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
                $actualNumbers[] = $number;
            }
        }
        return $actualNumbers;
    }
}
