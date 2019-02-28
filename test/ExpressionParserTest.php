<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\ExpressionParser;
use xKerman\Restricted\Source;

class ExpressionParserTest extends TestCase
{
    public function provideInvalidData()
    {
        return array(
            'empty string' => array(
                'input' => '',
            ),
            'invalid tag' => array(
                'input' => 'x:2:"aa";',
            ),
        );
    }

    /**
     * @covers \xKerman\Restricted\ExpressionParser
     * @dataProvider provideInvalidData
     */
    public function testParseFailure($input)
    {
        $this->expectException('\xKerman\Restricted\UnserializeFailedException');

        $source = new Source($input);
        $parser = new ExpressionParser();
        $parser->parse($source);
    }
}
