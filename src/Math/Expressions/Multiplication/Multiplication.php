<?php

namespace App\Math\Expressions\Multiplication;

use App\Math\Stack\Stack;
use App\Math\Expressions\Operator\Operator;

class Multiplication extends Operator
{

    protected $precidence = 5;

    public function operate(Stack $stack)
    {
        return $stack->pop()->operate($stack) * $stack->pop()->operate($stack);
    }

}