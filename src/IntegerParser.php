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
        $this->parser = new TypeConvertParser(
            new RegexpParser('/\Gi:([+-]?[0-9]+);/'),
            new IntegerConverter()
        );
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
        return $this->parser->parse($source);
    }
}
