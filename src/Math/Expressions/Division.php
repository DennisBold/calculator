<?php

namespace App\Math\Expressions;

use App\Math\Stack;

class Division extends Operator
{

    protected $precidence = 5;

    public function operate(Stack $stack)
    {
        $left  = $stack->pop()->operate($stack);
        $right = $stack->pop()->operate($stack);
        return $right / $left;
    }

}