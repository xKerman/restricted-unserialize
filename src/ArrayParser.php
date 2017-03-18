<?php
/**
 * parser for PHP serialized array
 */
namespace xKerman\Restricted;

/**
 * Parser for PHP serialiezed array
 */
class ArrayParser implements ParserInterface
{
    /** @var ParserInterface $expressionParser parser for unserialize expression */
    private $expressionParser;

    /** @var ParserInterface $integerParser parser for unserialized integer */
    private $integerParser;

    /** @var ParserInterface $stringParser parser for unserialized string */
    private $stringParser;

    /** @var ParserInterface $parser internal parser */
    private $parser;

    /**
     * constructor
     *
     * @param ParserInterface $expressionParser parser for unserialize expression
     * @param ParserInterface $integerParser    parser for unserialized integer
     * @param ParserInterface $stringParser     parser for unserialized string
     */
    public function __construct(
        ParserInterface $expressionParser,
        ParserInterface $integerParser,
        ParserInterface $stringParser
    ) {
        $this->expressionParser = $expressionParser;
        $this->integerParser = $integerParser;
        $this->stringParser = $stringParser;
        $this->parser = new TypeConvertParser(
            new RegexpParser('/\Ga:([+]?[0-9]+):{/'),
            new IntegerConverter()
        );
    }

    /**
     * parse given `$source` as PHP serialized array
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        list($length, $source) = $this->parser->parse($source);

        $result = array();
        for ($i = 0; $i < $length; ++$i) {
            list($key, $source) = $this->parseKey($source);
            list($value, $source) = $this->expressionParser->parse($source);
            $result[$key] = $value;
        }

        $source->consume('}');
        return array($result, $source);
    }

    /**
     * parse given `$source` as array key (s.t. integer|string)
     *
     * @param Source $source input
     * @return array
     * @throws UnserializeFailedException
     */
    private function parseKey($source)
    {
        switch ($source->peek()) {
            case 'i':
                return $this->integerParser->parse($source);
            case 's':
                return $this->stringParser->parse($source);
            default:
                return $source->triggerError();
        }
    }
}
