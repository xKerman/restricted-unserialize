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
        $this->setExpectedException($this->getClassName($exception));
    }

    private function getClassName($name)
    {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            $newName = str_replace('\\', '_', $name);
            return substr($newName, 1);
        }
        return $name;
    }
}
