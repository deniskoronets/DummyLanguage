<?php

namespace src\constructions;

class ScalarValue extends Construction
{
    public $type;

    public $value;

    public function __construct(string $type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}