<?php

namespace xKerman\Restricted\Test;

use PHPUnit\Framework\TestCase;

use xKerman\Restricted\Source;

/**
 * @coversDefaultClass \xKerman\Restricted\Source
 */
class SourceTest extends TestCase
{
    public function setUp()
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            if ($errno & E_USER_NOTICE) {
                throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
            }
        }, E_USER_NOTICE);
    }

    public function tearDown()
    {
        restore_error_handler();
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
