<?php

namespace src;

use Dekor\PhpSyntaxTreeBuilder\ASTNode;
use Dekor\PhpSyntaxTreeBuilder\Lexer;
use Dekor\PhpSyntaxTreeBuilder\Parser;
use src\constructions\Statements;
use src\core\exceptions\DummyRuntimeException;
use src\core\ExecuteMachine;
use Symfony\Component\Yaml\Yaml;

class DummyLanguage
{
    /**
     * Parse the program (string), validate constructions and turn it into a syntax tree
     * @param string $program
     * @return ASTNode
     * @throws \Dekor\PhpSyntaxTreeBuilder\Exceptions\LexerAnalyseException
     */
    private function parseProgram(string $program) : ASTNode
    {
        $lexer = new Lexer(Yaml::parseFile(__DIR__ . '/configs/lexems.yml'));
        $lexems = $lexer->parse($program);

        $parser = new Parser(
            ['startFrom' => 'g:statements'],
            Yaml::parseFile(__DIR__ . '/configs/grammary.yml')
        );

        return $parser->parse($lexems);
    }

    /**
     * @param string $program
     * @throws \Dekor\PhpSyntaxTreeBuilder\Exceptions\LexerAnalyseException
     */
    public function run(string $program)
    {
        file_put_contents('file.json', json_encode($this->parseProgram($program), JSON_PRETTY_PRINT));

        /**
         * @var Statements
         */
        $statements = (new SyntaxTreeConvertor($this->parseProgram($program)))->convert();

        file_put_contents('prepared.json', var_export($statements, 1));

        try {
            (new ExecuteMachine($statements))->execute();

        } catch (DummyRuntimeException $exception) {
            echo PHP_EOL . ' >>>>>>>>>>> Error! ' . $exception->getMessage() . PHP_EOL;
        }
    }
}