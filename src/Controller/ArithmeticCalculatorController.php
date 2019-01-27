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
     * we'll add sqrt(), pi(), avg(), min(), max().
     * @Route("/arithmetic/calculator", name="arithmetic_calculator")
     */
    public function index()
    {
        $numericalButtons = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $operatorButtons = ['+', '-', '/', '*', 'sqrt', 'pi', 'avg', 'min', 'max'];

        return $this->render('arithmetic_calculator/index.html.twig',
            ['numbers' => $numericalButtons, 'operators' => $operatorButtons]);

    }

    /**
     * This will be an endpoint for an AJAX request.
     * We expect the request to contain a calculation string, the special operation is optional.
     * We expect the calculation string to mathematical statement that we would need to execute.
     * Special operations cannot be used in all cases, since we expect them to be a specific value
     * Example: $calculationString = '(2+3)/2 * pi'
     * @param Request $request
     * @param string $calculationString
     * @param string $specialOperation
     * @return float|int
     */
    public function submitCalculation(Request $request, string $calculationString, string $specialOperation)
    {
        switch ($specialOperation) {
            case 'sqrt':
                return $this->sqrt((float)$calculationString);
            case 'avg':
                return $this->avg(explode(',', $calculationString));
            case 'min':
                return $this->min(explode(',', $calculationString));
            case 'max':
                return $this->max(explode(',', $calculationString));
            default:
                return $this->doMath($calculationString);
        }
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

    /**
     * Return the average of numbers in an array
     * @param array $numbers
     * @return float|int
     */
    protected function avg(array $numbers)
    {
        return array_sum($numbers) / count($numbers);
    }

    /**
     * Return the maximum value from an array of numbers
     * @param array $numbers
     * @return float|int
     */
    protected function max(array $numbers)
    {
        return max($numbers);
    }

    /**
     * Return the smallest value from an array of numbers
     * @param array $numbers
     * @return float|int
     */
    protected function min(array $numbers)
    {
        return min($numbers);
    }
}
