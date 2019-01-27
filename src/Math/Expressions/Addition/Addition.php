<?php

namespace App\Math\Expressions\Addition;

use App\Math\Stack\Stack;
use App\Math\Expressions\Operator\Operator;

class Addition extends Operator
{

    protected $precidence = 4;

    public function operate(Stack $stack)
    {
        return $stack->pop()->operate($stack) + $stack->pop()->operate($stack);
    }

}