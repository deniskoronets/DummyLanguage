<?php

namespace src\constructions;

class PrintExpression extends Construction
{
    public $expression;

    public function __construct(Expression $expression)
    {
        $this->expression = $expression;
    }
}