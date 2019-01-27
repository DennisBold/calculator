<?php

namespace App\Math;

use App\Math\Stack;
use App\Math\Expressions;
use App\Math\Expressions\Number;
use App\Math\Expressions\Addition;
use App\Math\Expressions\Subtraction;
use App\Math\Expressions\Multiplication;
use App\Math\Expressions\Division;
use App\Math\Expressions\Parenthesis;

class Math
{
    protected $variables = array();

    /**
     * @param $string
     * @return string
     * @throws \Exception
     */
    public function evaluate($string)
    {
        $stack = $this->parse($string);
        return $this->run($stack);
    }

    /**
     * @param $string
     * @return \Math\Stack
     * @throws \Exception
     */
    public function parse($string)
    {
        $tokens    = $this->tokenize($string);
        $output    = new Stack();
        $operators = new Stack();
        foreach ($tokens as $token) {
            $token      = $this->extractVariables($token);
            $expression = TerminalExpression::factory($token);
            if ($expression->isOperator()) {
                $this->parseOperator($expression, $output, $operators);
            } elseif ($expression->isParenthesis()) {
                $this->parseParenthesis($expression, $output, $operators);
            } else {
                $output->push($expression);
            }
        }
        while (($op = $operators->pop())) {
            if ($op->isParenthesis()) {
                throw new \RuntimeException('Mismatched Parenthesis');
            }
            $output->push($op);
        }
        return $output;
    }

    /**
     * @param $name
     * @param $value
     */
    public function registerVariable($name, $value)
    {
        $this->variables[$name] = $value;
    }

    /**
     * @param \Math\Stack $stack
     * @return string
     * @throws \Exception
     */
    public function run(Stack $stack)
    {
        while (($operator = $stack->pop()) && $operator->isOperator()) {
            $value = $operator->operate($stack);
            if (!is_null($value)) {
                $stack->push(TerminalExpression::factory($value));
            }
        }
        return $operator ? $operator->render() : $this->render($stack);
    }

    /**
     * @param $token
     * @return int|mixed
     */
    protected function extractVariables($token)
    {
        if ($token[0] == '$') {
            $key = substr($token, 1);
            return isset($this->variables[$key]) ? $this->variables[$key] : 0;
        }
        return $token;
    }

    /**
     * @param \Math\Stack $stack
     * @return string
     * @throws \RuntimeException
     */
    protected function render(Stack $stack)
    {
        $output = '';
        while (($el = $stack->pop())) {
            $output .= $el->render();
        }
        if ($output) {
            return $output;
        }
        throw new \RuntimeException('Could not render output');
    }

    /**
     * @param TerminalExpression $expression
     * @param \Math\Stack $output
     * @param \Math\Stack $operators
     */
    protected function parseParenthesis(TerminalExpression $expression, Stack $output, Stack $operators)
    {
        if ($expression->isOpen()) {
            $operators->push($expression);
        } else {
            $clean = false;
            while (($end = $operators->pop())) {
                if ($end->isParenthesis()) {
                    $clean = true;
                    break;
                } else {
                    $output->push($end);
                }
            }
            if (!$clean) {
                throw new \RuntimeException('Mismatched Parenthesis');
            }
        }
    }

    /**
     * @param TerminalExpression $expression
     * @param \Math\Stack $output
     * @param \Math\Stack $operators
     */
    protected function parseOperator(TerminalExpression $expression, Stack $output, Stack $operators)
    {
        $end = $operators->poke();
        if (!$end) {
            $operators->push($expression);
        } elseif ($end->isOperator()) {
            do {
                if ($expression->isLeftAssoc() && $expression->getPrecidence() <= $end->getPrecidence()) {
                    $output->push($operators->pop());
                } elseif (!$expression->isLeftAssoc() && $expression->getPrecidence() < $end->getPrecidence()) {
                    $output->push($operators->pop());
                } else {
                    break;
                }
            } while (($end = $operators->poke()) && $end->isOperator());
            $operators->push($expression);
        } else {
            $operators->push($expression);
        }
    }

    /**
     * @param $string
     * @return array|array[]|false|string[]
     */
    protected function tokenize($string)
    {
        $parts = preg_split('(([0-9]*\.?[0-9]+|\+|-|\(|\)|\*|\/)|\s+)', $string, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $parts = array_map('trim', $parts);
        return $parts;
    }
}