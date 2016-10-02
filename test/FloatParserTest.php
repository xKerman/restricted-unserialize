<?php

namespace xKerman\Restricted\Test;

use PHPUnit\Framework\TestCase;

use xKerman\Restricted\FloatParser;
use xKerman\Restricted\Source;

class FloatParserTest extends TestCase
{
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
                'input' => 'd:;',
            ],
            'missing semicolon' => [
                'input' => 'd:0',
            ],
            'sign only' => [
                'input' => 'd:+;',
            ],
            'multiple sign' => [
                'input' => 'd:++6;',
            ],
            'dot only' => [
                'input' => 'd:.;',
            ],
            'dot and exponential' => [
                'input' => 'd:.E;',
            ],
            'dot and exponential part' => [
                'input' => 'd:.E1;',
            ],
            'infinity with plus' => [
                'input' => 'd:+INF;',
            ],
            'nan with plus' => [
                'input' => 'd:+NAN;',
            ],
            'nan with plus' => [
                'input' => 'd:-NAN;',
            ],
            'float in exponential part' => [
                'input' => 'd:1.0e1.0;',
            ],
            'only exponential part' => [
                'input' => 'd:e1;'
            ],
        ];
    }

    /**
     * @covers \xKerman\Restricted\FloatParser
     * @dataProvider provideInvalidData
     * @expectedException \ErrorException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new FloatParser();
        $parser->parse($source);
    }
}
