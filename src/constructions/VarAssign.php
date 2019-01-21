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
    public function __construct(string $varName)
    {
        $this->varName = $varName;
    }

    /**
     * @param Expression $ex
     * @return $this
     */
    public function setExpression(Expression $ex)
    {
        $this->expression = $ex;
        return $this;
    }
}