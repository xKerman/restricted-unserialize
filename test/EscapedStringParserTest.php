<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\Source;
use xKerman\Restricted\EscapedStringParser;

/**
 * @coversDefaultClass \xKerman\Restricted\EscapedStringParser
 */
class EscapedStringParserTest extends \PHPUnit_Framework_TestCase
{
    public function provideInvalidData()
    {
        return array(
            'empty string' => array(
                'input' => '',
            ),
            'non string tag' => array(
                'input' => 'a:0:"";',
            ),
            'length is missing' => array(
                'input' => 'S::"";',
            ),
            'length is not number' => array(
                'input' => 'S:a:"";',
            ),
            'length is not integer' => array(
                'input' => 'S:1.0:"a";',
            ),
            'length is negative' => array(
                'input' => 'S:-1:"";',
            ),
            'length contains plus sign' => array(
                // see: https://github.com/php/php-src/commit/8522e2894edd52322148945261433e79a3ec3f88
                'input' => 'S:+1:"a";',
            ),
            'no quote' => array(
                'input' => 'S:1:a;',
            ),
            'open quote exist but close quote not exist' => array(
                'input' => 'S:1:"a;',
            ),
            'close quote exist but open quote not exist' => array(
                'input' => 'S:1:a";',
            ),
            'enclosed by single quote' => array(
                'input' => "S:1:'a';",
            ),
            'escape range error (first part)' => array(
                'input' => 'S:1:"\\ag";',
            ),
            'escape range error (second part)' => array(
                'input' => 'S:1:"\\ga";',
            ),
            'escaped string is short' => array(
                'input' => 'S:1:"\\1";',
            ),
        );
    }

    /**
     * @covers ::parse
     * @dataProvider provideInvalidData
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testParseFailure($input)
    {
        $source = new Source($input);
        $parser = new EscapedStringParser();
        $parser->parse($source);
    }
}
