<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\RegexpSubstringParser;
use xKerman\Restricted\Source;

class RegexpSubstringParserTest extends \PHPUnit_Framework_TestCase
{
    public function provideSuccessArgs()
    {
        return array(
            array(
                'input' => 'b:1;',
                'regexp' => '/\Gb:[10];/',
                'start' => 2,
                'length' => 1,
                'expected' => '1',
            ),
            array(
                'input' => 's:10:"aaaaaaaaaa";',
                'regexp' => '/\Gs:[+]?[0-9]+:"/',
                'start' => 2,
                'length' => -2,
                'expected' => '10',
            ),
        );
    }

    /**
     * @covers \xKerman\Restricted\RegexpSubstringParser
     * @dataProvider provideSuccessArgs
     */
    public function testParseSuccess($input, $regexp, $start, $length, $expected)
    {
        $source = new Source($input);
        $parser = new RegexpSubstringParser($regexp, $start, $length);
        $actual = $parser->parse($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \xKerman\Restricted\RegexpSubstringParser
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure()
    {
        $source = new Source('b:0;');
        $parser = new RegexpSubstringParser('/\Gb:1;/', 2, 1);
        $parser->parse($source);
    }
}
