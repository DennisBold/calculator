<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArithmeticCalculatorController extends AbstractController
{
    /**
     * Define a route that can handle operations.
     * We'll generate a map of buttons, since it's a calculator and we
     * want it to be useful, we'll add a few extra operators. Mainly
     * we'll add sqrt(), pi()
     * @Route("/arithmetic/calculator", name="arithmetic_calculator")
     */
    public function index()
    {
        $numericalButtons = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $operatorButtons  = ['+', '-', '/', '*', 'sqrt', 'pi'];

        return $this->render('arithmetic_calculator/index.html.twig',
            ['numbers' => $numericalButtons, 'operators' => $operatorButtons]);

    }

    /**
     * This will be an endpoint for an AJAX request.
     * We expect the request to contain a calculation string, the special operation is optional.
     * We expect the calculation string to mathematical statement that we would need to execute.
     * Special operations cannot be used in all cases, since we expect them to be a specific value
     * Example: $calculationString = '(2+3)/2 * pi'
     * @Route("/arithmetic/calculator/submit", name="arithmetic_calculator_submit")
     * @param Request $request
     * @param string $calculationString
     * @return float|int
     */
    public function submitCalculation(Request $request, string $calculationString)
    {
        return $this->doMath($calculationString);
    }

    /**
     * Square root a float value
     * @param float $number
     * @return float
     */
    protected function sqrt(float $number)
    {
        return sqrt($number);
    }

    protected function doMath($calculationString)
    {
        $string          = explode(',', $calculationString);
        $operatorButtons = ['+', '-', '/', '*', 'sqrt'];
        $operators       = [];
        $numbers         = [];
        foreach ($string as $value) {
            if ($value == 'pi') {
                $numbers[] = pi();
            } elseif (is_numeric($value)) {
                $numbers[] = $value;
            } elseif (in_array($value, $operatorButtons)) {
                $operators[] = $value;
            }
        }
        $numbers   = array_reverse($numbers);
        $operators = array_reverse($operators);
        foreach ($operators as $operator) {
            $numbers[] = $operator;
        }
    }

    protected function reversePolishCalculation(array $numberArray)
    {
        $numbers         = [];
        $operatorButtons = ['+', '-', '/', '*', 'sqrt'];
        $result          = '';
        foreach ($numberArray as $number) {
            if (is_numeric($number)) {
                $numbers[] = $number;
            } elseif (in_array($number, $operatorButtons)) {
                if (in_array($number, $operatorButtons)) {
                    switch ($number) {
                        case "+":
                            $result = array_pop($numbers) + array_pop($numbers);
                            break;
                        case "-":
                            $result = array_pop($numbers) - array_pop($numbers);
                            break;
                        case "*":
                            $result = array_pop($numbers) * array_pop($numbers);
                            break;
                        case "/":
                            $result = array_pop($numbers) / array_pop($numbers);
                            break;
                        case 'sqrt':
                            $result = $this->sqrt(array_pop($numbers));
                    }
                    array_push($numbers, $result);
                }
            }
        }
        return $result;
    }
}
