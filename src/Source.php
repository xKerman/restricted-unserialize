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
     * consume given string if it is as expected
     *
     * @param string $expected expected string
     * @return void
     * @throws UnserializeFailedException
     */
    public function consume($expected)
    {
        if (strpos($this->str, $expected, $this->current) !== $this->current) {
            return $this->triggerError();
        }
        $this->current += strlen($expected);
    }

    /**
     * read givin length substring
     *
     * @param integer $length length to read
     * @return string
     * @throws UnserializeFailedException
     */
    public function read($length)
    {
        if ($length < 0) {
            return $this->triggerError();
        }

        $result = substr($this->str, $this->current, $length);
        if (strlen($result) !== $length) {
            return $this->triggerError();
        }
        $this->current += $length;
        return $result;
    }

    /**
     * return matching string for given regexp
     *
     * @param string $regexp Regular Expression for expected substring
     * @return string
     */
    public function match($regexp)
    {
        $matched = preg_match($regexp, $this->str, $matches, 0, $this->current);
        if ($matched === 0 || $matched === false) {
            return '';
        }

        $this->current += strlen($matches[0]);
        return $matches[0];
    }
}
