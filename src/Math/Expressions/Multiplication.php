<?php

namespace Math\Expressions;

use Math\Stack;


class Multiplication extends Operator
{

    protected $precidence = 5;

    public function operate(Stack $stack)
    {
        return $stack->pop()->operate($stack) * $stack->pop()->operate($stack);
    }

}