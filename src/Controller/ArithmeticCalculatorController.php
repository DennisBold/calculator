<?php

namespace App\Controller;

use App\Math\Math;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArithmeticCalculatorController extends AbstractController
{
    /**
     * Define a route that can handle operations.
     * We'll generate a map of buttons, since it's a calculator and we
     * want it to be useful, we'll add a few extra operators. Mainly
     * we'll add pi()
     * @Route("/arithmetic/calculator", name="arithmetic_calculator")
     */
    public function index()
    {
        $numericalButtons = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $operatorButtons  = ['+', '-', '/', '*', 'pi'];

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
     * @return mixed
     * @throws \Exception
     */
    public function submitCalculation(Request $request)
    {
        $calculationString = str_replace(' ', ',', preg_replace('!\s+!', ' ', trim($request->get('calculationString'))));
        $result            = $this->doMath($calculationString);
        return new JsonResponse($result);
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
     * @param $calculationString
     * @return string
     * @throws \Exception
     */
    protected function doMath($calculationString)
    {
        $math                      = new Math();
        $explodedCalculationString = explode(',', $calculationString);
        foreach ($explodedCalculationString as $index => $pattern) {
            if ($pattern == 'pi') {
                $explodedCalculationString[$index] = pi();
            }
        }
        $result = $math->evaluate(implode(' ', $explodedCalculationString));

        return $result;
    }
}
