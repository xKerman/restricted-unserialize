<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\BooleanParser;
use xKerman\Restricted\Source;

class BooleanParserTest extends \PHPUnit_Framework_TestCase
{
    public function provideInvalidData()
    {
        return [
            'empty string' => [
                'input' => '',
            ],
            'not boolean' => [
                'input' => 'i:0;',
            ],
            'missing tag' => [
                'input' => ':0;',
            ],
            'missing value' => [
                'input' => 'b:;',
            ],
            'missing semicolon' => [
                'input' => 'b:0',
            ],
            'value is not boolean' => [
                'input' => 'b:2;',
            ],
        ];
    }

    /**
     * @covers \xKerman\Restricted\BooleanParser
     * @dataProvider provideInvalidData
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new BooleanParser();
        $parser->parse($source);
    }
}
