<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted;

class UnserializeTest extends \PHPUnit_Framework_TestCase
{
    public function testNull()
    {
        $this->assertNull(Restricted\unserialize('N;'));
    }

    public function provideDataForBooleanTest()
    {
        return array(
            'true' => array(
                'input' => 'b:1;',
                'expected' => true,
            ),
            'false' => array(
                'input' => 'b:0;',
                'expected' => false,
            ),
        );
    }

    /**
     * @dataProvider provideDataForBooleanTest
     */
    public function testBoolean($input, $expected)
    {
        $this->assertSame($expected, Restricted\unserialize($input));
    }

    public function provideDataForIntegerTest()
    {
        return array(
            'positive integer w/o plus sign' => array(
                'input' => 'i:1;',
                'expected' => 1,
            ),
            'positive integer w/ plus sign' => array(
                'input' => 'i:+1;',
                'expected' => 1,
            ),
            'positive integer greater than 9' => array(
                'input' => 'i:10;',
                'expected' => 10,
            ),
            'negative integer' => array(
                'input' => 'i:-1;',
                'expected' => -1,
            ),
            'integer w/ leading zero' => array(
                'input' => 'i:0987;',
                'expected' => 987,
            ),
        );
    }

    /**
     * @dataProvider provideDataForIntegerTest
     */
    public function testInteger($input, $expected)
    {
        $this->assertSame($expected, Restricted\unserialize($input));
    }

    public function provideDataForDoubleTest()
    {
        return array(
            'positive double w/o plus sign and point' => array(
                'input' => 'd:1;',
                'expected' => 1.0,
            ),
            'positive double w/ plus sign' => array(
                'input' => 'd:+1.5;',
                'expected' => 1.5,
            ),
            'negative double' => array(
                'input' => 'd:-1.5;',
                'expected' => -1.5,
            ),
            'positive double w/o dicimal part' => array(
                'input' => 'd:2.;',
                'expected' => 2.0,
            ),
            'positive double w/o integer part and plus sign' => array(
                'input' => 'd:.5;',
                'expected' => 0.5,
            ),
            'positive double w/o integer part w/ plus sign' => array(
                'input' => 'd:+.5;',
                'expected' => 0.5,
            ),
            'negative double w/o integer part' => array(
                'input' => 'd:-.5;',
                'expected' => -0.5,
            ),
            'positive double represented as exponential notion w/o plus sign' => array(
                'input' => 'd:1.0e10;',
                'expected' =>1e10,
            ),
            'positive double represented as exponential notion w/ plus sign' => array(
                'input' => 'd:+1.0e10;',
                'expected' =>1e10,
            ),
            'negative double represented as exponential notion' => array(
                'input' => 'd:-1.0e10;',
                'expected' => -1e10,
            ),

            'positive double represented as exponential notion w/ positive exponential part' => array(
                'input' => 'd:25E+2;',
                'expected' => 2500.0,
            ),
            'positive double represented as exponential notion w/ negative exponential part' => array(
                'input' => 'd:25E-2;',
                'expeced' => 0.25,
            ),
            'positive infinity' => array(
                'input' => 'd:INF;',
                'expected' => INF,
            ),
            'negative infinity' => array(
                'input' => 'd:-INF;',
                'expected' => -INF,
            ),
        );
    }

    /**
     * @dataProvider provideDataForDoubleTest
     */
    public function testDouble($input, $expected)
    {
        $this->assertSame($expected, Restricted\unserialize($input));
    }

    public function testNan()
    {
        $this->assertTrue(is_nan(Restricted\unserialize('d:NAN;')));
    }

    public function provideDataForStringTest()
    {
        return array(
            'empty string' => array(
                'input' => 's:0:"";',
                'expected' => '',
            ),
            'single character (a)' => array(
                'input' => 's:1:"a";',
                'expected' => 'a',
            ),
            'single character (double quote)' => array(
                'input' => serialize('"'),
                'expected' => '"',
            ),
            'single character (single quote)' => array(
                'input' => serialize("'"),
                'expected' => "'",
            ),
            'single character (null byte)' => array(
                'input' => serialize("\x00"),
                'expected' => "\x00",
            ),
            'japanese character' => array(
                'input' => serialize('こんにちは'),
                'expected' => 'こんにちは',
            ),
        );
    }

    /**
     * @dataProvider provideDataForStringTest
     */
    public function testString($input, $expected)
    {
        $this->assertSame($expected, Restricted\unserialize($input));
    }

    public function provideDataForEscapedStringTest()
    {
        return array(
            'empty string' => array(
                'input' => 'S:0:"";',
                'expected' => '',
            ),
            'single character (a, not escaped)' => array(
                'input' => 'S:1:"a";',
                'expected' => 'a',
            ),
            'single character (a, escaped)' => array(
                'input' => 'S:1:"\61";',
                'expected' => 'a',
            ),
            'single character (j, escaped, upper)' => array(
                'input' => 'S:1:"\6A";',
                'expected' => 'j',
            ),
            'single character (j, escaped, lower)' => array(
                'input' => 'S:1:"\6a";',
                'expected' => 'j',
            ),
            'single character (double quote)' => array(
                'input' => 'S:1:""";',
                'expected' => '"',
            ),
            'single character (null byte)' => array(
                'input' => 'S:1:"\00";',
                'expected' => "\x00",
            ),
            'single character (\xFF)' => array(
                'input' => 'S:1:"\FF";',
                'expected' => "\xff",
            ),
        );
    }

    /**
     * @dataProvider provideDataForEscapedStringTest
     */
    public function testEscapedString($input, $expected)
    {
        $this->assertSame($expected, Restricted\unserialize($input));
        $this->assertSame($expected, unserialize($input));
    }

    public function provideDataForArrayTest()
    {
        return array(
            'empty array' => array(
                'input' => 'a:0:{}',
                'expected' => array(),
            ),
            'one element array' => array(
                'input' => 'a:1:{i:0;s:1:"a";}',
                'expected' => array('a'),
            ),
            'nested array' => array(
                'input' => serialize(array(array(), array(array(1), null), "a" => array("b" => "c"))),
                'expected' => array(array(), array(array(1), null), "a" => array("b" => "c")),
            ),
            'escaped key array' => array(
                'input' => 'a:1:{S:1:"\62";N;}',
                'expected' => array('b' => null),
            ),
        );
    }

    /**
     * @dataProvider provideDataForArrayTest
     */
    public function testArray($input, $expected)
    {
        $this->assertSame($expected, Restricted\unserialize($input));
    }
}
