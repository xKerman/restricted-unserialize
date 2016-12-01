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
}
