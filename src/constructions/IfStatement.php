<?php

namespace src\constructions;

class IfStatement extends Construction
{
    /**
     * @var Expression
     */
    public $expression;

    /**
     * @var Statements
     */
    public $trueStatements;

    /**
     * @var Statements
     */
    public $elseStatements;

    public function __construct(Expression $expression, Statements $trueStatements, Statements $elseStatements)
    {
        $this->expression = $expression;
        $this->trueStatements = $trueStatements;
        $this->elseStatements = $elseStatements;
    }
}