<?php

class xKerman_Restricted_Test_ExpressionParserTest extends PHPUnit_Framework_TestCase
{
    public function provideInvalidData()
    {
        return array('empty string' => array('input' => ''), 'invalid tag' => array('input' => 'x:2:"aa";'));
    }
    /**
     * @covers \xKerman\Restricted\ExpressionParser
     * @dataProvider provideInvalidData
     * @expectedException xKerman_Restricted_UnserializeFailedException
     */
    public function testParseFailure($input)
    {
        $source = new xKerman_Restricted_Source($input);
        $parser = new xKerman_Restricted_ExpressionParser();
        $parser->parse($source);
    }
}