<?php

namespace src\constructions;

class VarUsage
{
    public $varName;

    public function __construct(string $varName)
    {
        $this->varName = $varName;
    }
}