<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\ArrayParser;
use xKerman\Restricted\ExpressionParser;
use xKerman\Restricted\IntegerParser;
use xKerman\Restricted\StringParser;
use xKerman\Restricted\Source;

class ArrayParserTest extends \PHPUnit_Framework_TestCase
{
    public function provideInvalidData()
    {
        return array(
            'empty string' => array(
                'input' => '',
            ),
            'not array' => array(
                'input' => 'N;',
            ),
            'array length is missing' => array(
                'input' => 'a::{}',
            ),
            'array length is not number' => array(
                'input' => 'a:s:{}',
            ),
            'array length is not integer' => array(
                'input' => 'a:1.0:{}',
            ),
            'array length is negative' => array(
                'input' => 'a:-1:{}',
            ),
            'array length is smaller than actual items' => array(
                'input' => 'a:0:{i:0;s:1:"a";}',
            ),
            'array length is greater than actual items' => array(
                'input' => 'a:2:{i:0;s:1:"a";}',
            ),
            'array key is not integer nor string' => array(
                'input' => 'a:1:{N;i:0;}',
            ),
            'open brace not exist' => array(
                'input' => 'a:0:}',
            ),
            'close brace not exist' => array(
                'input' => 'a:0:{',
            ),
            'braces not exist' => array(
                'input' => 'a:0:',
            ),
            'value not exist' => array(
                'input' => 'a:1:{i:0;}',
            ),
        );
    }

    /**
     * @covers \xKerman\Restricted\ArrayParser
     * @dataProvider provideInvalidData
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new ArrayParser(new ExpressionParser(), new IntegerParser(), new StringParser());
        $parser->parse($source);
    }
}
