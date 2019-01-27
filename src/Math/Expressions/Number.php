<?php

namespace App\Math\Expressions;

use App\Math\Stack;
use App\Math\TerminalExpression;


class Number extends TerminalExpression
{

    public function operate(Stack $stack)
    {
        return $this->value;
    }

}