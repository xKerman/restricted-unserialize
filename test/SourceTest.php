<?php

namespace xKerman\Restricted\Test;

use xKerman\Restricted\Source;

/**
 * @coversDefaultClass \xKerman\Restricted\Source
 */
class SourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructFailed()
    {
        new Source(2);
    }

    public function testPeek()
    {
        $source = new Source('hello');
        $this->assertSame('h', $source->peek());
    }

    /**
     * @covers ::next
     */
    public function testNext()
    {
        $source = new Source('hello');
        $source->next();
        $this->assertSame('e', $source->peek());
    }

    public function provideConsumeSucceeded()
    {
        return array(
            array(
                'input' => 'hello',
                'consumption' => 'h',
                'expected' => 'e',
            ),
            array(
                'input' => 'hello',
                'consumption' => 'he',
                'expected' => 'l',
            ),
        );
    }

    /**
     * @covers ::consume
     * @dataProvider provideConsumeSucceeded
     */
    public function testConsumeSucceeded($input, $consumption, $expected)
    {
        $source = new Source($input);
        $source->consume($consumption);
        $this->assertSame($expected, $source->peek());
    }

    /**
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testConsumeFailure()
    {
        $source = new Source('hello');
        $source->consume('e');
    }

    /**
     * @covres ::read
     */
    public function testRead()
    {
        $source = new Source('abcdefg');
        $actual = $source->read(5);
        $this->assertSame('abcde', $actual);
        $this->assertSame('f', $source->peek());
    }

    public function provideReadFailure()
    {
        return array(
            array(
                'input' => 'abc',
                'length' => 5,
            ),
            array(
                'input' => 'abc',
                'length' => -1,
            ),
        );
    }

    /**
     * @covers ::read
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     * @dataProvider provideReadFailure
     */
    public function testReadFailure($input, $length)
    {
        $source = new Source($input);
        $source->read($length);
    }

    /**
     * @covers ::match
     */
    public function testMatch()
    {
        $source = new Source('abcde');
        $result = $source->match('/\G\w{3}/');
        $this->assertSame('abc', $result);
        $this->assertSame('d', $source->peek());
    }

    /**
     * @covers ::match
     */
    public function testMatchFailure()
    {
        $source = new Source('abcde12345');
        $result = $source->match('/\G\d/');
        $this->assertSame('', $result);
        $this->assertSame('a', $source->peek());
    }
}
