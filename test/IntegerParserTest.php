<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\IntegerParser;
use xKerman\Restricted\Source;

class IntegerParserTest extends \PHPUnit_Framework_TestCase
{
    public function provideInvalidData()
    {
        return array(
            'empty string' => array(
                'input' => '',
            ),
            'not integer' => array(
                'input' => 'N;',
            ),
            'missing tag' => array(
                'input' => ':0;',
            ),
            'missing value' => array(
                'input' => 'i:;',
            ),
            'missing semicolon' => array(
                'input' => 'i:0',
            ),
            'sign only' => array(
                'input' => 'i:+;',
            ),
            'multiple sign' => array(
                'input' => 'i:++6;',
            ),
            'float value' => array(
                'input' => 'i:1.0;',
            ),
            'hex value' => array(
                'input' => 'i:0x50;',
            ),
            'binary value' => array(
                'input' => 'i:0b111;',
            ),
        );
    }

    /**
     * @covers \xKerman\Restricted\IntegerParser
     * @covers \xKerman\Restricted\NumberLiteralParser
     * @covers \xKerman\Restricted\NumberStringParser
     * @dataProvider provideInvalidData
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new IntegerParser();
        $parser->parse($source);
    }
}
