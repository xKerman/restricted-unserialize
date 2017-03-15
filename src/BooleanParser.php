<?php
/**
 * parser for PHP serialized boolean
 */
namespace xKerman\Restricted;

/**
 * Parser for PHP serialized boolean
 */
class BooleanParser implements ParserInterface
{
    /** @var ParserInterface $parser internal parser for boolean parsing */
    private $parser;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->parser = new RegexpSubstringParser('/\Gb:[01];/', 2, 1);
    }

    /**
     * parse given `$source` as PHP serialized boolean
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $result = $this->parser->parse($source);
        return array($result === '1', $source);
    }
}
