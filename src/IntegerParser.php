<?php
/**
 * parser for PHP serialized integer
 */
namespace xKerman\Restricted;

/**
 * Parser for PHP serialized integer
 */
class IntegerParser implements ParserInterface
{
    /** @var ParserInterface $parser internal parser for integer parsing */
    private $parser;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->parser = new RegexpSubstringParser('/\Gi:[+-]?[0-9]+;/', 2, -1);
    }

    /**
     * parse given `$source` as PHP serialized integer
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $result = $this->parser->parse($source);
        return array(intval($result, 10), $source);
    }
}
