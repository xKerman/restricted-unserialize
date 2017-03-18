<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\BooleanParser;
use xKerman\Restricted\Source;

class BooleanParserTest extends \PHPUnit_Framework_TestCase
{
    public function provideInvalidData()
    {
        return array(
            'empty string' => array(
                'input' => '',
            ),
            'not boolean' => array(
                'input' => 'i:0;',
            ),
            'missing tag' => array(
                'input' => ':0;',
            ),
            'missing value' => array(
                'input' => 'b:;',
            ),
            'missing semicolon' => array(
                'input' => 'b:0',
            ),
            'value is not boolean' => array(
                'input' => 'b:2;',
            ),
        );
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
