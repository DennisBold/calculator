<?php

namespace App\Math\Expressions\Division;

use App\Math\Stack\Stack;
use App\Math\Expressions\Operator\Operator;

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