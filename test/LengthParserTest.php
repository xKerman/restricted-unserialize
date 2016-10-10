<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\LengthParser;
use xKerman\Restricted\Source;

class LengthParserTest extends \PHPUnit_Framework_TestCase
{
    public function provideValidData()
    {
        return [
            'w/o plus sign' => [
                'input' => '10',
                'expected' => 10,
            ],
            'w/ plus sign' => [
                'input' => '+15',
                'expected' => 15,
            ],
        ];
    }

    /**
     * @covers \xKerman\Restricted\LengthParser
     * @dataProvider provideValidData
     */
    public function testParserSucceeded($input, $expected)
    {
        $source = new Source($input);
        $parser = new LengthParser();
        list($actual,) = $parser->parse($source);
        $this->assertSame($expected, $actual);
    }

    public function provideInvalidData()
    {
        return [
            'not number' => [
                'input' => 'aaa',
            ],
            'negative integer' => [
                'input' => '-1',
            ],
        ];
    }

    /**
     * @covers \xKerman\Restricted\LengthParser
     * @dataProvider provideInvalidData
     * @expectedException xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new LengthParser();
        $parser->parse($source);
    }
}
