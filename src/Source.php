<?php

namespace xKerman\Restricted;

class Source
{
    private $str;

    private $current;

    public function __construct($str)
    {
        $this->str = $str;
        $this->current = 0;
    }

    public function triggerError()
    {
        $bytes = strlen($this->str);
        trigger_error("unserialize(): Error at offset {$this->current} of {$bytes} bytes");
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
            $this->triggerError();
        }
        $this->next();
    }
}
