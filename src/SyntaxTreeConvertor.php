<?php

namespace src;

use Dekor\PhpSyntaxTreeBuilder\ASTNode;
use src\constructions\Expression;
use src\constructions\IfStatement;
use src\constructions\PrintExpression;
use src\constructions\ScalarValue;
use src\constructions\Statements;
use src\constructions\VarAssign;
use src\constructions\VarUsage;

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
                $this->statements->appendStatement(
                    new IfStatement(
                        $this->convertNode($astNode->subGroups['g:expression']),
                        $this->convertNode($astNode->subGroups['g:statements']),
                        $this->convertNode($astNode->subGroups['g:else']->subGroups['g:statements'])
                    )
                );
                break;

            case 'g:expression':
                return new Expression(
                    $this->convertNode(reset($astNode->subGroups)),
                    $astNode->subGroups['g:_expression']->lexems[0]->value ?? null,
                    $astNode->subGroups['g:_expression'] ? $this->convertNode(reset($astNode->subGroups['g:_expression']->subGroups)) : null
                );

            case 'g:print':
                $this->statements->appendStatement(
                    new PrintExpression(
                        $this->convertNode($astNode->subGroups['g:expression'])
                    )
                );
                break;

            case 'g:expression_var_usage':
                return new VarUsage($astNode->lexems[0]->value);

            case 'g:expression_scalar_num':
                return new ScalarValue('int', $astNode->lexems[0]->value);

            case 'g:expression_scalar_string':
                return new ScalarValue('string', $astNode->lexems[0]->value);

            case 'g:var_assign':
                $this->statements->appendStatement(
                    new VarAssign(
                        $astNode->lexems[1]->value,
                        $this->convertNode($astNode->subGroups['g:expression'])
                    )
                );
                break;

            default:
                throw new \Exception('Unknown construction: ' . $astNode->group);
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

        if (empty($this->rootNode->subGroups['g:statements'])) {
            return $this->statements;
        }

        foreach ($this->convertNode($this->rootNode->subGroups['g:statements'])->statements as $statement) {
            $this->statements->appendStatement($statement);
        }

        return $this->statements;
    }
}