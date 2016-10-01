<?php

namespace xKerman\Restricted\Test;

use PHPUnit\Framework\TestCase;

use xKerman\Restricted\NullParser;
use xKerman\Restricted\Source;

class NullParserTest extends TestCase
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
            'not null' => [
                'input' => 'i:0;',
            ],
            'missing tag' => [
                'input' => ';',
            ],
            'missing semicolon' => [
                'input' => 'N',
            ],
        ];
    }

    /**
     * @cover \xKerman\Restricted\NullParser
     * @dataProvider provideInvalidData
     * @expectedException \ErrorException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new NullParser();
        $parser->parse($source);
    }
}
