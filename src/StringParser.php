<?php
/**
 * parser for serialized string
 */
namespace xKerman\Restricted;

/**
 * Parser class for parse serialized PHP stirng
 */
class StringParser implements ParserInterface
{
    /** @var ParserInterface $parser internal parser */
    private $parser;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->parser = new TypeConvertParser(
            new RegexpSubstringParser('/\Gs:[+]?[0-9]+:"/', 2, -2),
            new IntegerConverter()
        );
    }

    /**
     * parse give `$source` as PHP serialized string
     *
     * @param Source $source parser input
     * @return array parser result
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        list($length, $source) = $this->parser->parse($source);
        $result = $source->read($length);
        $source->consume('";');

        return array($result, $source);
    }
}
