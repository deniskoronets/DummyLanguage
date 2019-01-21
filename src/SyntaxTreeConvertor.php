<?php

namespace src;

use Dekor\PhpSyntaxTreeBuilder\ASTNode;
use src\constructions\Statements;

class SyntaxTreeConvertor
{
    /**
     * @var ASTNode
     */
    public $rootNode;

    /**
     * @var Statements
     */
    public $statements;

    public function __construct(ASTNode $astRootNode)
    {
        $this->rootNode = $astRootNode;
        $this->statements = new Statements();
    }

    public function convertNode(ASTNode $astNode)
    {
        //
    }

    public function convert() : Statements
    {
        if ($this->rootNode->group != 'g:statements') {
            throw new \Exception('Wrong type of root node');
        }



        return new Statements([]);
    }
}