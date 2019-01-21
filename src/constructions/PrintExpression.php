<?php

namespace src\constructions;

class PrintExpression
{
    public $expression;

    public function __construct(Expression $expression)
    {
        $this->expression = $expression;
    }
}