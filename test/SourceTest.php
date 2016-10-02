<?php

namespace xKerman\Restricted\Test;

use PHPUnit\Framework\TestCase;

use xKerman\Restricted\Source;

/**
 * @coversDefaultClass \xKerman\Restricted\Source
 */
class SourceTest extends TestCase
{
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

    /**
     * @covers ::consume
     */
    public function testConsumeSucceeded()
    {
        $source = new Source('hello');
        $source->consume('h');
        $this->assertSame('e', $source->peek());
    }

    /**
     * @expectedException \ErrorException
     */
    public function testConsumeFailure()
    {
        $source = new Source('hello');
        $source->consume('e');
    }
}
