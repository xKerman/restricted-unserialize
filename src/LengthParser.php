<?php
/**
 * provide parser for length
 */
namespace xKerman\Restricted;

/**
 * Parser for length, non-negative integer
 */
class LengthParser implements ParserInterface
{
    /** @var ParserInterface $parser internal parser for length */
    private $parser;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->parser = new NumberLiteralParser();
    }

    /**
     * parse given `$source` as length, non-negative integer
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        list($length, $source) = $this->parser->parse($source);
        if ($length < 0) {
            return $source->triggerError();
        }
        return array($length, $source);
    }
}
