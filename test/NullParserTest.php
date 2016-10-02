<?php

namespace xKerman\Restricted\Test;

use PHPUnit\Framework\TestCase;

use xKerman\Restricted\NullParser;
use xKerman\Restricted\Source;

class NullParserTest extends TestCase
{
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
