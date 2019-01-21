<?php

namespace src;

use Dekor\PhpSyntaxTreeBuilder\ASTNode;
use Dekor\PhpSyntaxTreeBuilder\Lexer;
use Dekor\PhpSyntaxTreeBuilder\Parser;
use src\constructions\Statements;
use Symfony\Component\Yaml\Yaml;

class DummyLanguage
{
    /**
     * Parse the program (string), validate constructions and turn it into a syntax tree
     * @param string $program
     * @return ASTNode
     * @throws \Dekor\PhpSyntaxTreeBuilder\Exceptions\LexerAnalyseException
     */
    private function parseProgramm(string $program) : ASTNode
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
        /**
         * @var Statements
         */
        $statements = (new SyntaxTreeConvertor($this->parseProgramm($program)))->convert();
    }
}