<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Annotation\Route;

class PolishNotationCalculatorController extends AbstractController
{
    /**
     * Define a route that can handle operations.
     * We'll generate a map of buttons and operators that work with reverse polish notation.
     * Reverse polish notation is essentially operations in ASM.
     * Example: 53+ which would equal 8. 482+- = (4+8) - 2 = 10.
     * @Route("/polish/notation/calculator", name="polish_notation_calculator")
     */
    public function index()
    {
        $numericalButtons = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $operatorButtons = ['+', '-', '/', '*'];

        return $this->render('arithmetic_calculator/index.html.twig',
            ['numbers' => $numericalButtons, 'operators' => $operatorButtons]);
    }

    /**
     * This will be an endpoint for an AJAX request.
     * We expect the request to contain a bitwise calculation.
     * This is a base two statement and will most often contain two values consisting of either 1 or 0.
     * We'll then use a gate logic table to decide on the outcome.
     * Example: $calculationString = '1,0,+'
     * @param Request $request
     * @param string $calculationString
     * @return float|int
     */
    public function submitCalculation(Request $request, string $calculationString)
    {
        $numbers = explode(',', $calculationString);
        $acceptableOperators = ['+', '-', '/', '*'];
        $operatorNumbers = [];
        $calculationResult = '';
        // Check we have an array
        if (is_array($numbers)) {
            if (in_array(end($numbers), $acceptableOperators)) {
                $this->errorUnusableOperator('Operators should be at the end.');
            }
            foreach ($numbers as $index => $number) {
                if (is_numeric($number)) {
                    $operationNumbers[] = $number;
                } elseif (in_array($number, $acceptableOperators)) {
                    $first_number = array_pop($operationNumbers);
                    $second_number = array_pop($operationNumbers);
                    switch ($number) {
                        case '+':
                            $calculationResult = $first_number + $second_number;
                            break;
                        case '-':
                            $calculationResult = $first_number - $second_number;
                            break;
                        case '/':
                            $calculationResult = $first_number / $second_number;
                            break;
                        case '*':
                            $calculationResult = $first_number * $second_number;
                            break;
                    }
                    array_push($operationNumbers, $calculationResult);
                } else {
                    $this->errorInsufficientParameters('The value of ' . $number . ' is not acceptable.');
                }
            }
        }
        return $calculationResult;
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
