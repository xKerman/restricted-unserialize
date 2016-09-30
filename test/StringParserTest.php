<?php

namespace xKerman\Restricted\Test;

use PHPUnit\Framework\TestCase;

use xKerman\Restricted\Source;
use xKerman\Restricted\StringParser;

/**
 * @coversDefaultClass \xKerman\Restricted\StringParser
 */
class StringParserTest extends TestCase
{
    public function setUp()
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            if ($errno & E_USER_NOTICE) {
                throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
            }
        }, E_USER_NOTICE);
    }

    public function tearDown()
    {
        restore_error_handler();
    }

    public function provideInvalidData()
    {
        return [
            'empty string' => [
                'input' => '',
            ],
            'non string tag' => [
                'input' => 'a:0:"";',
            ],
            'length is not number' => [
                'input' => 's:a:"";',
            ],
            'length is not integer' => [
                'input' => 's:1.0:"a";',
            ],
            'length is negative' => [
                'input' => 's:-1:"";',
            ],
            'no quote' => [
                'input' => 's:1:a;',
            ],
            'open quote exist but close quote not exist' => [
                'input' => 's:1:"a;',
            ],
            'close quote exist but open quote not exist' => [
                'input' => 's:1:a";',
            ],
            'enclosed by single quote' => [
                'input' => "s:1:'a';",
            ],
        ];
    }

    /**
     * @covers ::parse
     * @dataProvider provideInvalidData
     * @expectedException \ErrorException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new StringParser();
        $parser->parse($source);
    }
}
