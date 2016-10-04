<?php
/**
 * Input for parser
 */
namespace xKerman\Restricted;

/**
 * Parser Input
 */
class Source
{
    /** @var string $str given string to deserialize */
    private $str;

    /** @var int $current current position of parser */
    private $current;

    /**
     * constructor
     *
     * @param string $str parser input
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
     * throw error with currnt position
     *
     * @return void
     * @throws UnserializeFailedException
     */
    public function triggerError()
    {
        $bytes = strlen($this->str);
        throw new UnserializeFailedException("unserialize(): Error at offset {$this->current} of {$bytes} bytes");
    }

    /**
     * return current character
     *
     * @return string
     */
    public function peek()
    {
        return substr($this->str, $this->current, 1);
    }

    /**
     * go ahead one character
     *
     * @return void
     */
    public function next()
    {
        ++$this->current;
    }

    /**
     * consume one character if it is as expected
     *
     * @param string $expected expected character
     * @return void
     * @throws UnserializeFailedException
     */
    public function consume($expected)
    {
        if ($expected !== $this->peek()) {
            return $this->triggerError();
        }
        $this->next();
    }
}
