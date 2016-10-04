<?php
/**
 * parser for number
 */
namespace xKerman\Restricted;

/**
 * Parser for number, return result as integer
 */
class NumberLiteralParser implements ParserInterface
{
    /** @var ParserInterface $parer internal parser for number */
    private $parser;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->parser = new NumberStringParser();
    }

    /**
     * parse given `$source` as number
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        list($result, $source) = $this->parser->parse($source);
        return [intval($result, 10), $source];
    }
}
