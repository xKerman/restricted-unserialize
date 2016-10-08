<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\ArrayParser;
use xKerman\Restricted\Source;

class ArrayParserTest extends \PHPUnit_Framework_TestCase
{
    public function provideInvalidData()
    {
        return [
            'empty string' => [
                'input' => '',
            ],
            'not array' => [
                'input' => 'N;',
            ],
            'array length is missing' => [
                'input' => 'a::{}',
            ],
            'array length is not number' => [
                'input' => 'a:s:{}',
            ],
            'array length is not integer' => [
                'input' => 'a:1.0:{}',
            ],
            'array length is negative' => [
                'input' => 'a:-1:{}',
            ],
            'array length is smaller than actual items' => [
                'input' => 'a:0:{i:0;s:1:"a";}',
            ],
            'array length is greater than actual items' => [
                'input' => 'a:2:{i:0;s:1:"a";}',
            ],
            'array key is not integer nor string' => [
                'input' => 'a:1:{N;i:0;}',
            ],
            'open brace not exist' => [
                'input' => 'a:0:}',
            ],
            'close brace not exist' => [
                'input' => 'a:0:{',
            ],
            'braces not exist' => [
                'input' => 'a:0:',
            ],
            'value not exist' => [
                'input' => 'a:1:{i:0;}',
            ],
        ];
    }

    /**
     * @covers \xKerman\Restricted\ArrayParser
     * @dataProvider provideInvalidData
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new ArrayParser();
        $parser->parse($source);
    }
}
