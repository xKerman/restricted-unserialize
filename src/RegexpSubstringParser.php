<?php
/**
 * parser for regexp match and then substring
 */
namespace xKerman\Restricted;

/**
 * Parser that first process regexp match, and then substring the result
 */
class RegexpSubstringParser implements ParserInterface
{
    /** @var string $regexp regexp for matching */
    private $regexp;

    /** @var integer $start start position of substring */
    private $start;

    /** @var integer $length length of substring (see PHP `substr` manual) */
    private $length;

    /**
     * constructor
     *
     * @param string  $regexp regexp for matching
     * @param integer $start  start position of substring
     * @param integer $length length of substring
     */
    public function __construct($regexp, $start, $length)
    {
        $this->regexp = $regexp;
        $this->start = $start;
        $this->length = $length;
    }

    /**
     * parse given `$source` and return substring result
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $result = $source->match($this->regexp);
        return substr($result, $this->start, $this->length);
    }
}
