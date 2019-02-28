<?php

class xKerman_Restricted_Test_ExpressionParserTest extends xKerman_Restricted_Test_TestCase
{
    public function provideInvalidData()
    {
        return array('empty string' => array('input' => ''), 'invalid tag' => array('input' => 'x:2:"aa";'));
    }
    /**
     * @covers xKerman_Restricted_ExpressionParser
     * @dataProvider provideInvalidData
     */
    public function testParseFailure($input)
    {
        $this->expectException('\\xKerman\\Restricted\\UnserializeFailedException');
        $source = new xKerman_Restricted_Source($input);
        $parser = new xKerman_Restricted_ExpressionParser();
        $parser->parse($source);
    }
}