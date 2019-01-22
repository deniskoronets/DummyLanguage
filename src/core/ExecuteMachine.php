<?php

namespace src\core;

use src\constructions\Expression;
use src\constructions\IfStatement;
use src\constructions\PrintExpression;
use src\constructions\ScalarValue;
use src\constructions\Statements;
use src\constructions\VarAssign;
use src\constructions\VarUsage;
use src\core\exceptions\UndefinedVariableException;

class ExecuteMachine
{
    /**
     * @var Statements
     */
    private $statements;

    /**
     * Variables for current context
     * @var array
     */
    private $context = [];

    public function __construct(Statements $statements, array $context = [])
    {
        $this->statements = $statements;
        $this->context = $context;
    }

    public function resolveScalar($expressionPart)
    {
        switch (get_class($expressionPart)) {
            case ScalarValue::class:
                return $expressionPart->value;

            case VarUsage::class:

                if (!isset($this->context[$expressionPart->varName])) {
                    throw new UndefinedVariableException($expressionPart->varName);
                }

                return $this->context[$expressionPart->varName];

            default:
                throw new \Exception('Unknown expression part');
        }
    }

    private function executeExpression(Expression $expression)
    {
        $leftValue = $this->resolveScalar($expression->left);

        if ($expression->operator === null) {
            return $leftValue;
        }

        $rightValue = $this->resolveScalar($expression->right);

        switch ($expression->operator) {
            case '+':
                return $leftValue + $rightValue;

            case '-':
                return $leftValue - $rightValue;

            case '*':
                return $leftValue * $rightValue;

            case '/':
                return $leftValue / $rightValue;

            default:
                throw new \Exception('Unknown operation');
        }
    }

    public function execute()
    {
        foreach ($this->statements->statements as $statement) {
            switch (get_class($statement)) {
                case IfStatement::class:
                    if ($this->executeExpression($statement->expression)) {
                        (new ExecuteMachine($statement->trueStatements, $this->context))->execute();
                    } else {
                        (new ExecuteMachine($statement->elseStatements, $this->context))->execute();
                    }
                    break;

                case PrintExpression::class:
                    echo $this->executeExpression($statement->expression) . PHP_EOL;
                    break;

                case VarAssign::class:
                    $this->context[$statement->varName] = $this->executeExpression($statement->expression);
                    break;
            }
        }
    }
}