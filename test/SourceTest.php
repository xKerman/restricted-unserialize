<?php

namespace xKerman\Restricted\Test;

use PHPUnit\Framework\TestCase;

use xKerman\Restricted\Source;

/**
 * @coversDefaultClass \xKerman\Restricted\Source
 */
class SourceTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructFailed()
    {
        new Source(2);
    }

    public function provideConsumeSucceeded()
    {
        return array(
            array(
                'input' => 'hello',
                'consumption' => 'h',
            ),
            array(
                'input' => 'hello',
                'consumption' => 'he',
            ),
        );
    }

    /**
     * @covers ::consume
     * @dataProvider provideConsumeSucceeded
     */
    public function testConsumeSucceeded($input, $consumption)
    {
        $source = new Source($input);
        $source->consume($consumption, strlen($consumption));
        $this->assertTrue(true);
    }

    /**
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testConsumeFailure()
    {
        $source = new Source('hello');
        $source->consume('e', strlen('e'));
    }

    /**
     * @covres ::read
     */
    public function testRead()
    {
        $source = new Source('abcdefg');
        $actual = $source->read(5);
        $this->assertSame('abcde', $actual);
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
        $source = new Source('1234hoge');
        $result = $source->match('/\G1([0-9]+)/');
        $this->assertSame('234', $result[0]);
    }

    /**
     * @covers ::match
     * @expectedException \xKerman\Restricted\UnserializeFailedException
     */
    public function testMatchFailure()
    {
        $source = new Source('abcde12345');
        $result = $source->match('/\G\d/');
    }
}
