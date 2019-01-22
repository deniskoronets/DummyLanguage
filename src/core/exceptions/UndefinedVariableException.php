<?php

namespace src\core\exceptions;

class UndefinedVariableException extends DummyRuntimeException
{
    public function __construct(string $varName)
    {
        parent::__construct('Var ' . $varName . ' has been used before declaration');
    }
}