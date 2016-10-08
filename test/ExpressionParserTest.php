<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\ExpressionParser;
use xKerman\Restricted\Source;

class ExpressionParserTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new ExpressionParser();
        $parser->parse($source);
    }
}
