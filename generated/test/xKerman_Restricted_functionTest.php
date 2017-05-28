<?php

class xKerman_Restricted_Test_UnserializeTest extends PHPUnit_Framework_TestCase
{
    public function testNull()
    {
        $this->assertNull(xKerman_Restricted_unserialize('N;'));
    }
    public function provideDataForInvalidNull()
    {
        return array('empty string' => array('input' => ''), 'missing tag' => array('input' => ';'), 'missing semicolon' => array('input' => 'N'));
    }
    /**
     * @dataProvider provideDataForInvalidNull
     * @expectedException xKerman_Restricted_UnserializeFailedException
     */
    public function testInvalidNull($input)
    {
        xKerman_Restricted_unserialize($input);
    }
    public function provideDataForBooleanTest()
    {
        return array('true' => array('input' => 'b:1;', 'expected' => true), 'false' => array('input' => 'b:0;', 'expected' => false));
    }
    /**
     * @dataProvider provideDataForBooleanTest
     */
    public function testBoolean($input, $expected)
    {
        $this->assertSame($expected, xKerman_Restricted_unserialize($input));
    }
    public function provideDataForIntegerTest()
    {
        return array('positive integer w/o plus sign' => array('input' => 'i:1;', 'expected' => 1), 'positive integer w/ plus sign' => array('input' => 'i:+1;', 'expected' => 1), 'positive integer greater than 9' => array('input' => 'i:10;', 'expected' => 10), 'negative integer' => array('input' => 'i:-1;', 'expected' => -1), 'integer w/ leading zero' => array('input' => 'i:0987;', 'expected' => 987));
    }
    public function provideDataForInvalidBooleanTest()
    {
        return array('empty string' => array('input' => ''), 'missing tag' => array('input' => ':0;'), 'missing value' => array('input' => 'b:;'), 'missing semicolon' => array('input' => 'b:0'), 'value is not boolean' => array('input' => 'b:2;'));
    }
    /**
     * @dataProvider provideDataForInvalidBooleanTest
     * @expectedException xKerman_Restricted_UnserializeFailedException
     */
    public function testInvalidBoolean($input)
    {
        xKerman_Restricted_unserialize($input);
    }
    /**
     * @dataProvider provideDataForIntegerTest
     */
    public function testInteger($input, $expected)
    {
        $this->assertSame($expected, xKerman_Restricted_unserialize($input));
    }
    public function provideDataForInvalidInteger()
    {
        return array('empty string' => array('input' => ''), 'missing tag' => array('input' => ':0;'), 'missing value' => array('input' => 'i:;'), 'missing semicolon' => array('input' => 'i:0'), 'sign only' => array('input' => 'i:+;'), 'multiple sign' => array('input' => 'i:++6;'), 'float value' => array('input' => 'i:1.0;'), 'hex value' => array('input' => 'i:0x50;'), 'binary value' => array('input' => 'i:0b111;'));
    }
    /**
     * @dataProvider provideDataForInvalidInteger
     * @expectedException xKerman_Restricted_UnserializeFailedException
     */
    public function testInvalidInteger($input)
    {
        xKerman_Restricted_unserialize($input);
    }
    public function provideDataForFloatTest()
    {
        return array('positive double w/o plus sign and point' => array('input' => 'd:1;', 'expected' => 1.0), 'positive double w/ plus sign' => array('input' => 'd:+1.5;', 'expected' => 1.5), 'negative double' => array('input' => 'd:-1.5;', 'expected' => -1.5), 'positive double w/o dicimal part' => array('input' => 'd:2.;', 'expected' => 2.0), 'positive double w/o integer part and plus sign' => array('input' => 'd:.5;', 'expected' => 0.5), 'positive double w/o integer part w/ plus sign' => array('input' => 'd:+.5;', 'expected' => 0.5), 'negative double w/o integer part' => array('input' => 'd:-.5;', 'expected' => -0.5), 'positive double represented as exponential notion w/o plus sign' => array('input' => 'd:1.0e10;', 'expected' => 10000000000.0), 'positive double represented as exponential notion w/ plus sign' => array('input' => 'd:+1.0e10;', 'expected' => 10000000000.0), 'negative double represented as exponential notion' => array('input' => 'd:-1.0e10;', 'expected' => -10000000000.0), 'positive double represented as exponential notion w/ positive exponential part' => array('input' => 'd:25E+2;', 'expected' => 2500.0), 'positive double represented as exponential notion w/ negative exponential part' => array('input' => 'd:25E-2;', 'expeced' => 0.25), 'positive infinity' => array('input' => 'd:INF;', 'expected' => INF), 'negative infinity' => array('input' => 'd:-INF;', 'expected' => -INF));
    }
    /**
     * @dataProvider provideDataForFloatTest
     */
    public function testFloat($input, $expected)
    {
        $this->assertSame($expected, xKerman_Restricted_unserialize($input));
    }
    public function provideDataForInvalidFloat()
    {
        return array('empty string' => array('input' => ''), 'missing tag' => array('input' => ':0;'), 'missing value' => array('input' => 'd:;'), 'missing semicolon' => array('input' => 'd:0'), 'sign only' => array('input' => 'd:+;'), 'multiple sign' => array('input' => 'd:++6;'), 'dot only' => array('input' => 'd:.;'), 'dot and exponential' => array('input' => 'd:.E;'), 'dot and exponential part' => array('input' => 'd:.E1;'), 'infinity with plus' => array('input' => 'd:+INF;'), 'nan with plus' => array('input' => 'd:+NAN;'), 'nan with plus' => array('input' => 'd:-NAN;'), 'float in exponential part' => array('input' => 'd:1.0e1.0;'), 'only exponential part' => array('input' => 'd:e1;'));
    }
    /**
     * @dataProvider provideDataForInvalidFloat
     * @expectedException xKerman_Restricted_UnserializeFailedException
     */
    public function testInvalidFloat($input)
    {
        xKerman_Restricted_unserialize($input);
    }
    public function testNan()
    {
        $this->assertTrue(is_nan(xKerman_Restricted_unserialize('d:NAN;')));
    }
    public function provideDataForStringTest()
    {
        return array('empty string' => array('input' => 's:0:"";', 'expected' => ''), 'single character (a)' => array('input' => 's:1:"a";', 'expected' => 'a'), 'single character (double quote)' => array('input' => serialize('"'), 'expected' => '"'), 'single character (single quote)' => array('input' => serialize("'"), 'expected' => "'"), 'single character (null byte)' => array('input' => serialize("\0"), 'expected' => "\0"), 'japanese character' => array('input' => serialize('こんにちは'), 'expected' => 'こんにちは'));
    }
    /**
     * @dataProvider provideDataForStringTest
     */
    public function testString($input, $expected)
    {
        $this->assertSame($expected, xKerman_Restricted_unserialize($input));
    }
    public function provideDataForInvalidString()
    {
        return array('empty string' => array('input' => ''), 'length is missing' => array('input' => 's::"";'), 'length is not number' => array('input' => 's:a:"";'), 'length is not integer' => array('input' => 's:1.0:"a";'), 'length is negative' => array('input' => 's:-1:"";'), 'length contains plus sign' => array('input' => 's:+1:"a";'), 'no quote' => array('input' => 's:1:a;'), 'open quote exist but close quote not exist' => array('input' => 's:1:"a;'), 'close quote exist but open quote not exist' => array('input' => 's:1:a";'), 'enclosed by single quote' => array('input' => "s:1:'a';"));
    }
    /**
     * @dataProvider provideDataForInvalidString
     * @expectedException xKerman_Restricted_UnserializeFailedException
     */
    public function testInvalidString($input)
    {
        xKerman_Restricted_unserialize($input);
    }
    public function provideDataForEscapedStringTest()
    {
        return array('empty string' => array('input' => 'S:0:"";', 'expected' => ''), 'single character (a, not escaped)' => array('input' => 'S:1:"a";', 'expected' => 'a'), 'single character (a, escaped)' => array('input' => 'S:1:"\\61";', 'expected' => 'a'), 'single character (j, escaped, upper)' => array('input' => 'S:1:"\\6A";', 'expected' => 'j'), 'single character (j, escaped, lower)' => array('input' => 'S:1:"\\6a";', 'expected' => 'j'), 'single character (double quote)' => array('input' => 'S:1:""";', 'expected' => '"'), 'single character (null byte)' => array('input' => 'S:1:"\\00";', 'expected' => "\0"), 'single character (\\xFF)' => array('input' => 'S:1:"\\FF";', 'expected' => "�"));
    }
    /**
     * @dataProvider provideDataForEscapedStringTest
     */
    public function testEscapedString($input, $expected)
    {
        $this->assertSame($expected, xKerman_Restricted_unserialize($input));
    }
    public function provideDataForInvalidEscapedString()
    {
        return array('empty string' => array('input' => ''), 'length is missing' => array('input' => 'S::"";'), 'length is not number' => array('input' => 'S:a:"";'), 'length is not integer' => array('input' => 'S:1.0:"a";'), 'length is negative' => array('input' => 'S:-1:"";'), 'length contains plus sign' => array('input' => 'S:+1:"a";'), 'no quote' => array('input' => 'S:1:a;'), 'open quote exist but close quote not exist' => array('input' => 'S:1:"a;'), 'close quote exist but open quote not exist' => array('input' => 'S:1:a";'), 'enclosed by single quote' => array('input' => "S:1:'a';"), 'escape range error (first part)' => array('input' => 'S:1:"\\ag";'), 'escape range error (second part)' => array('input' => 'S:1:"\\ga";'), 'escaped string is short' => array('input' => 'S:1:"\\1";'));
    }
    /**
     * @dataProvider provideDataForInvalidEscapedString
     * @expectedException xKerman_Restricted_UnserializeFailedException
     */
    public function testInvalidEscapedString($input)
    {
        xKerman_Restricted_unserialize($input);
    }
    public function provideDataForArrayTest()
    {
        return array('empty array' => array('input' => 'a:0:{}', 'expected' => array()), 'one element array' => array('input' => 'a:1:{i:0;s:1:"a";}', 'expected' => array('a')), 'nested array' => array('input' => serialize(array(array(), array(array(1), null), "a" => array("b" => "c"))), 'expected' => array(array(), array(array(1), null), "a" => array("b" => "c"))), 'escaped key array' => array('input' => 'a:1:{S:1:"\\62";N;}', 'expected' => array('b' => null)));
    }
    /**
     * @dataProvider provideDataForArrayTest
     */
    public function testArray($input, $expected)
    {
        $this->assertSame($expected, xKerman_Restricted_unserialize($input));
    }
    public function provideDataForInvalidArrayTest()
    {
        return array('empty string' => array('input' => ''), 'array length is missing' => array('input' => 'a::{}'), 'array length is not number' => array('input' => 'a:s:{}'), 'array length is not integer' => array('input' => 'a:1.0:{}'), 'array length is negative' => array('input' => 'a:-1:{}'), 'length contains plus sign' => array('input' => 'a:+0:{}'), 'array length is smaller than actual items' => array('input' => 'a:0:{i:0;s:1:"a";}'), 'array length is greater than actual items' => array('input' => 'a:2:{i:0;s:1:"a";}'), 'array key is not integer nor string' => array('input' => 'a:1:{N;i:0;}'), 'open brace not exist' => array('input' => 'a:0:}'), 'close brace not exist' => array('input' => 'a:0:{'), 'braces not exist' => array('input' => 'a:0:'), 'value not exist' => array('input' => 'a:1:{i:0;}'));
    }
    /**
     * @dataProvider provideDataForInvalidArrayTest
     * @expectedException xKerman_Restricted_UnserializeFailedException
     */
    public function testInvalidArray($input)
    {
        xKerman_Restricted_unserialize($input);
    }
}