<?php

namespace App\Math\Expressions;

use App\Math\Stack;

class Addition extends Operator
{

    protected $precidence = 4;

    public function operate(Stack $stack)
    {
        return $stack->pop()->operate($stack) + $stack->pop()->operate($stack);
    }

}