<?php

namespace xKerman\Restricted\Test;

use PHPUnit\Framework\TestCase;

use function xKerman\Restricted\unserialize;

class UnserializeTest extends TestCase
{
    public function testNull()
    {
        $this->assertNull(unserialize('N;'));
    }

    public function provideDataForBooleanTest()
    {
        return [
            'true' => [
                'input' => 'b:1;',
                'expected' => true,
            ],
            'false' => [
                'input' => 'b:0;',
                'expected' => false,
            ],
        ];
    }

    /**
     * @dataProvider provideDataForBooleanTest
     */
    public function testBoolean($input, $expected)
    {
        $this->assertSame($expected, unserialize($input));
    }

    public function provideDataForIntegerTest()
    {
        return [
            'positive integer w/o plus sign' => [
                'input' => 'i:1;',
                'expected' => 1,
            ],
            'positive integer w/ plus sign' => [
                'input' => 'i:+1;',
                'expected' => 1,
            ],
            'positive integer greater than 9' => [
                'input' => 'i:10;',
                'expected' => 10,
            ],
            'negative integer' => [
                'input' => 'i:-1;',
                'expected' => -1,
            ],
        ];
    }

    /**
     * @dataProvider provideDataForIntegerTest
     */
    public function testInteger($input, $expected)
    {
        $this->assertSame($expected, unserialize($input));
    }

    public function provideDataForDoubleTest()
    {
        return [
            'positive double w/o plus sign and point' => [
                'input' => 'd:1;',
                'expected' => 1.0,
            ],
            'positive double w/ plus sign' => [
                'input' => 'd:+1.5;',
                'expected' => 1.5,
            ],
            'negative double' => [
                'input' => 'd:-1.5;',
                'expected' => -1.5,
            ],
            'positive double w/o dicimal part' => [
                'input' => 'd:2.;',
                'expected' => 2.0,
            ],
            'positive double w/o integer part and plus sign' => [
                'input' => 'd:.5;',
                'expected' => 0.5,
            ],
            'positive double w/o integer part w/ plus sign' => [
                'input' => 'd:+.5;',
                'expected' => 0.5,
            ],
            'negative double w/o integer part' => [
                'input' => 'd:-.5;',
                'expected' => -0.5,
            ],
            'positive double represented as exponential notion w/o plus sign' => [
                'input' => 'd:1.0e10;',
                'expected' =>1e10,
            ],
            'positive double represented as exponential notion w/ plus sign' => [
                'input' => 'd:+1.0e10;',
                'expected' =>1e10,
            ],
            'negative double represented as exponential notion' => [
                'input' => 'd:-1.0e10;',
                'expected' => -1e10,
            ],

            'positive double represented as exponential notion w/ positive exponential part' => [
                'input' => 'd:25E+2;',
                'expected' => 2500.0,
            ],
            'positive double represented as exponential notion w/ negative exponential part' => [
                'input' => 'd:25E-2;',
                'expeced' => 0.25,
            ],
        ];
    }

    /**
     * @dataProvider provideDataForDoubleTest
     */
    public function testDouble($input, $expected)
    {
        $this->assertSame($expected, unserialize($input));
    }

    public function provideDataForStringTest()
    {
        return [
            'empty string' => [
                'input' => 's:0:"";',
                'expected' => '',
            ],
            'single character (a)' => [
                'input' => 's:1:"a";',
                'expected' => 'a',
            ],
            'single character (double quote)' => [
                'input' => serialize('"'),
                'expected' => '"',
            ],
            'single character (single quote)' => [
                'input' => serialize("'"),
                'expected' => "'",
            ],
            'single character (null byte)' => [
                'input' => serialize("\x00"),
                'expected' => "\x00",
            ],
            'japanese character' => [
                'input' => serialize('こんにちは'),
                'expected' => 'こんにちは',
            ],
        ];
    }

    /**
     * @dataProvider provideDataForStringTest
     */
    public function testString($input, $expected)
    {
        $this->assertSame($expected, unserialize($input));
    }

    public function provideDataForArrayTest()
    {
        return [
            'empty array' => [
                'input' => 'a:0:{}',
                'expected' => [],
            ],
            'one element array' => [
                'input' => 'a:1:{i:0;s:1:"a";}',
                'expected' => ['a'],
            ],
            'nested array' => [
                'input' => serialize([[], [[1], null], "a" => ["b" => "c"]]),
                'expected' => [[], [[1], null], "a" => ["b" => "c"]],
            ],
        ];
    }

    /**
     * @dataProvider provideDataForArrayTest
     */
    public function testArray($input, $expected)
    {
        $this->assertSame($expected, unserialize($input));
    }
}
