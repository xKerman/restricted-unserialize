<?php
/**
 * parse and then convert type for result
 */
namespace xKerman\Restricted;

/**
 * Parser that returns result as converted
 */
class TypeConvertParser implements ParserInterface
{
    /** @var ParserInterface $parser parser for given input */
    private $parser;

    /** @var ConverterInterface $converter converter for parser result */
    private $converter;

    /**
     * constructor
     *
     * @param ParserInterface    $parser    parser
     * @param ConverterInterface $converter converter for parser result
     */
    public function __construct(ParserInterface $parser, ConverterInterface $converter)
    {
        $this->parser = $parser;
        $this->converter = $converter;
    }

    /**
     * parse given `$source` and then convert type
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        list($result, $source) = $this->parser->parse($source);
        return array($this->converter->convert($result), $source);
    }
}
