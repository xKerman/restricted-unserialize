<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\Source;
use xKerman\Restricted\StringParser;

/**
 * @coversDefaultClass \xKerman\Restricted\StringParser
 */
class StringParserTest extends \PHPUnit_Framework_TestCase
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
                'input' => 's::"";',
            ),
            'length is not number' => array(
                'input' => 's:a:"";',
            ),
            'length is not integer' => array(
                'input' => 's:1.0:"a";',
            ),
            'length is negative' => array(
                'input' => 's:-1:"";',
            ),
            'length contains plus sign' => array(
                // see: https://github.com/php/php-src/commit/8522e2894edd52322148945261433e79a3ec3f88
                'input' => 's:+1:"a";',
            ),
            'no quote' => array(
                'input' => 's:1:a;',
            ),
            'open quote exist but close quote not exist' => array(
                'input' => 's:1:"a;',
            ),
            'close quote exist but open quote not exist' => array(
                'input' => 's:1:a";',
            ),
            'enclosed by single quote' => array(
                'input' => "s:1:'a';",
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
        $parser = new StringParser();
        $parser->parse($source);
    }
}
