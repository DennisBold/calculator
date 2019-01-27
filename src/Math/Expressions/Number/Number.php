<?php

namespace App\Math\Expressions\Number;

use App\Math\Stack\Stack;
use App\Math\TerminalExpression\TerminalExpression;


class Number extends TerminalExpression
{

    public function operate(Stack $stack)
    {
        return $this->value;
    }

}