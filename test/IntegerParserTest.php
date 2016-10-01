<?php

namespace xKerman\Restricted\Test;

use PHPUnit\Framework\TestCase;

use xKerman\Restricted\IntegerParser;
use xKerman\Restricted\Source;

class IntegerParserTest extends TestCase
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
            'not integer' => [
                'input' => 'N;',
            ],
            'missing tag' => [
                'input' => ':0;',
            ],
            'missing value' => [
                'input' => 'i:;',
            ],
            'missing semicolon' => [
                'input' => 'i:0',
            ],
            'sign only' => [
                'input' => 'i:+;',
            ],
            'multiple sign' => [
                'input' => 'i:++6;',
            ],
            'float value' => [
                'input' => 'i:1.0;',
            ],
            'hex value' => [
                'input' => 'i:0x50;',
            ],
            'binary value' => [
                'input' => 'i:0b111;',
            ],
        ];
    }

    /**
     * @covers \xKerman\Restricted\IntegerParser
     * @covers \xKerman\Restricted\NumberParser
     * @dataProvider provideInvalidData
     * @expectedException \ErrorException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new IntegerParser();
        $parser->parse($source);
    }
}
