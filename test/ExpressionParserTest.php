<?php

namespace xKerman\Restricted\Test;

use PHPUnit\Framework\TestCase;

use xKerman\Restricted\ExpressionParser;
use xKerman\Restricted\Source;

class ExpressionParserTest extends TestCase
{
    public function provideInvalidData()
    {
        return [
            'empty string' => [
                'input' => '',
            ],
            'invalid tag' => [
                'input' => 'x:2:"aa";',
            ],
        ];
    }

    /**
     * @covers \xKerman\Restricted\ExpressionParser
     * @dataProvider provideInvalidData
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new ExpressionParser();
        $parser->parse($source);
    }
}
