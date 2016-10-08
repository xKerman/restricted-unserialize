<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\FloatParser;
use xKerman\Restricted\Source;

class FloatParserTest extends \PHPUnit_Framework_TestCase
{
    public function provideInvalidData()
    {
        return array(
            'empty string' => array(
                'input' => '',
            ),
            'not integer' => array(
                'input' => 'N;',
            ),
            'missing tag' => array(
                'input' => ':0;',
            ),
            'missing value' => array(
                'input' => 'd:;',
            ),
            'missing semicolon' => array(
                'input' => 'd:0',
            ),
            'sign only' => array(
                'input' => 'd:+;',
            ),
            'multiple sign' => array(
                'input' => 'd:++6;',
            ),
            'dot only' => array(
                'input' => 'd:.;',
            ),
            'dot and exponential' => array(
                'input' => 'd:.E;',
            ),
            'dot and exponential part' => array(
                'input' => 'd:.E1;',
            ),
            'infinity with plus' => array(
                'input' => 'd:+INF;',
            ),
            'nan with plus' => array(
                'input' => 'd:+NAN;',
            ),
            'nan with plus' => array(
                'input' => 'd:-NAN;',
            ),
            'float in exponential part' => array(
                'input' => 'd:1.0e1.0;',
            ),
            'only exponential part' => array(
                'input' => 'd:e1;'
            ),
        );
    }

    /**
     * @covers \xKerman\Restricted\FloatParser
     * @dataProvider provideInvalidData
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new FloatParser();
        $parser->parse($source);
    }
}
