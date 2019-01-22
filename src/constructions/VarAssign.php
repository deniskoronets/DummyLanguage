<?php

namespace src\constructions;

class VarAssign extends Construction
{
    /**
     * @var string
     */
    public $varName;

    /**
     * @var Expression
     */
    public $expression;

    /**
     * VarAssign constructor.
     * @param string $varName
     */
    public function __construct(string $varName, Expression $expression)
    {
        $this->varName = $varName;
        $this->expression = $expression;
    }
}