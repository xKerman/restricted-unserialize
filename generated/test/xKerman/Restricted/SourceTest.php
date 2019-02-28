<?php

/**
 * @coversDefaultClass \xKerman\Restricted\Source
 */
class xKerman_Restricted_Test_SourceTest extends xKerman_Restricted_Test_TestCase
{
    public function testConstructFailed()
    {
        $this->expectException('\\InvalidArgumentException');
        new xKerman_Restricted_Source(2);
    }
    public function provideConsumeSucceeded()
    {
        return array(array('input' => 'hello', 'consumption' => 'h'), array('input' => 'hello', 'consumption' => 'he'));
    }
    /**
     *
     * @dataProvider provideConsumeSucceeded
     */
    public function testConsumeSucceeded($input, $consumption)
    {
        $source = new xKerman_Restricted_Source($input);
        $source->consume($consumption, strlen($consumption));
        $this->assertTrue(true);
    }
    public function testConsumeFailure()
    {
        $this->expectException('\\xKerman\\Restricted\\UnserializeFailedException');
        $source = new xKerman_Restricted_Source('hello');
        $source->consume('e', strlen('e'));
    }
    /**
     * @covres ::read
     */
    public function testRead()
    {
        $source = new xKerman_Restricted_Source('abcdefg');
        $actual = $source->read(5);
        $this->assertSame('abcde', $actual);
    }
    public function provideReadFailure()
    {
        return array(array('input' => 'abc', 'length' => 5), array('input' => 'abc', 'length' => -1));
    }
    /**
     *
     * @dataProvider provideReadFailure
     */
    public function testReadFailure($input, $length)
    {
        $this->expectException('\\xKerman\\Restricted\\UnserializeFailedException');
        $source = new xKerman_Restricted_Source($input);
        $source->read($length);
    }
    /**
     *
     */
    public function testMatch()
    {
        $source = new xKerman_Restricted_Source('1234hoge');
        $result = $source->match('/\\G1([0-9]+)/');
        $this->assertSame('234', $result[0]);
    }
    /**
     *
     */
    public function testMatchFailure()
    {
        $this->expectException('\\xKerman\\Restricted\\UnserializeFailedException');
        $source = new xKerman_Restricted_Source('abcde12345');
        $result = $source->match('/\\G\\d/');
    }
}