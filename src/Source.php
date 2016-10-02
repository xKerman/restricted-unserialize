<?php

namespace xKerman\Restricted;

class Source
{
    private $str;

    private $current;

    /**
     * @param string $str
     * @throws \InvalidArgumentException
     */
    public function __construct($str)
    {
        if (!is_string($str)) {
            throw new \InvalidArgumentException('expected string, but got: ' . gettype($str));
        }
        $this->str = $str;
        $this->current = 0;
    }

    /**
     * @throws UnserializeFailedException
     */
    public function triggerError()
    {
        $bytes = strlen($this->str);
        throw new UnserializeFailedException("unserialize(): Error at offset {$this->current} of {$bytes} bytes");
    }

    public function peek()
    {
        return substr($this->str, $this->current, 1);
    }

    public function next()
    {
        ++$this->current;
    }

    public function consume($expected)
    {
        if ($expected !== $this->peek()) {
            return $this->triggerError();
        }
        $this->next();
    }
}
