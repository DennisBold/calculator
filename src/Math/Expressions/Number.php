<?php

namespace Math\Expressions;

use Math\Stack;
use Math\TerminalExpression;


class Number extends TerminalExpression
{

    public function operate(Stack $stack)
    {
        return $this->value;
    }

}