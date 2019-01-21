<?php

namespace src\constructions;

class Statements
{
    /**
     * @var array
     */
    public $statements;

    public function __construct(array $statements = [])
    {
        $this->statements = $statements;
    }

    /**
     * @param Construction $statement
     */
    public function appendStatement(Construction $statement)
    {
        $this->statements[] = $statement;
    }
}