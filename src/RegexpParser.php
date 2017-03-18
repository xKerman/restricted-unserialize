<?php
/**
 * parser for regexp match and then substring
 */
namespace xKerman\Restricted;

/**
 * Parser that first process regexp match, and then substring the result
 */
class RegexpParser implements ParserInterface
{
    /** @var string $regexp regexp for matching */
    private $regexp;

    /**
     * constructor
     *
     * @param string $regexp regexp for matching
     */
    public function __construct($regexp)
    {
        $this->regexp = $regexp;
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
        return array($result, $source);
    }
}
