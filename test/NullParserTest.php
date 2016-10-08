<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\NullParser;
use xKerman\Restricted\Source;

class NullParserTest extends \PHPUnit_Framework_TestCase
{
    public function provideInvalidData()
    {
        return array(
            'empty string' => array(
                'input' => '',
            ),
            'not null' => array(
                'input' => 'i:0;',
            ),
            'missing tag' => array(
                'input' => ';',
            ),
            'missing semicolon' => array(
                'input' => 'N',
            ),
        );
    }

    /**
     * @covers \xKerman\Restricted\NullParser
     * @dataProvider provideInvalidData
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new NullParser();
        $parser->parse($source);
    }
}
