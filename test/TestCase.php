<?php

namespace xKerman\Restricted\Test;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * call `setExpectedException` if `expectException` is not defined
     *
     * @param str   $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        if ($name !== 'expectException') {
            throw new \BadMethodCallException("undefined method: ${name}");
        }

        $exception = $arguments[0];
        $this->setExpectedException($exception);
    }
}
