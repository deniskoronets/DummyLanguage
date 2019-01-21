<?php

namespace src\constructions;

class Expression extends Construction
{
    public $left;

    public $operator;

    public $right;

    public function __construct($left, $operator, $right)
    {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }
}