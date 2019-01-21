<?php

namespace src;

use Dekor\PhpSyntaxTreeBuilder\ASTNode;
use src\constructions\Expression;
use src\constructions\IfStatement;
use src\constructions\PrintExpression;
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

    /**
     * @param ASTNode $astNode
     * @return Expression|IfStatement|PrintExpression|Statements
     * @throws \Exception
     */
    public function convertNode(ASTNode $astNode)
    {
        switch ($astNode->group) {
            case 'g:statements':
                return (new SyntaxTreeConvertor($astNode))->convert();

            case 'g:if':
                return new IfStatement(
                    $this->convertNode($astNode->subGroups['g:expression']),
                    $this->convertNode($astNode->subGroups['g:statements']),
                    $this->convertNode($astNode->subGroups['g:else']->subGroups['g:statements'])
                );

            case 'g:expression':
                return new Expression(
                    reset($astNode->subGroups),
                    $astNode->subGroups['g:_expressions']->lexems[0]['value'] ?? null,
                    $astNode->subGroups['g:_expressions'] ? reset($astNode->subGroups['g:_expressions']->subGroups) : null
                );

            case 'g:print':
                return new PrintExpression(
                    $this->convertNode($astNode->subGroups['g:expression'])
                );
        }
    }

    /**
     * @return Statements
     * @throws \Exception
     */
    public function convert() : Statements
    {
        if ($this->rootNode->group != 'g:statements') {
            throw new \Exception('Wrong type of root node');
        }

        $this->convertNode($this->rootNode->subGroups['g:statement']);

        return $this->statements;
    }
}