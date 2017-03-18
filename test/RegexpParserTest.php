<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\RegexpParser;
use xKerman\Restricted\Source;

class RegexpParserTest extends \PHPUnit_Framework_TestCase
{
    public function provideSuccessArgs()
    {
        return array(
            array(
                'input' => 'b:1;',
                'regexp' => '/\Gb:([10]);/',
                'expected' => '1',
            ),
            array(
                'input' => 's:10:"aaaaaaaaaa";',
                'regexp' => '/\Gs:([+]?[0-9]+):"/',
                'expected' => '10',
            ),
        );
    }

    /**
     * @covers \xKerman\Restricted\RegexpParser
     * @dataProvider provideSuccessArgs
     */
    public function testParseSuccess($input, $regexp, $expected)
    {
        $source = new Source($input);
        $parser = new RegexpParser($regexp);
        list($actual, $source) = $parser->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \xKerman\Restricted\RegexpParser
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure()
    {
        $source = new Source('b:0;');
        $parser = new RegexpParser('/\Gb:1;/');
        $parser->parse($source);
    }
}
