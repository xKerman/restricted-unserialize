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

    /** @var ParserInterface $escapedStringParser parser for unserialized escaped string */
    private $escapedStringParser;

    /** @var integer */
    const CLOSE_BRACE_LENGTH = 1;

    /**
     * constructor
     *
     * @param ParserInterface $expressionParser    parser for unserialize expression
     * @param ParserInterface $integerParser       parser for unserialized integer
     * @param ParserInterface $stringParser        parser for unserialized string
     * @param ParserInterface $escapedStringParser parser for unserialized escaped string
     */
    public function __construct(
        ParserInterface $expressionParser,
        ParserInterface $integerParser,
        ParserInterface $stringParser,
        ParserInterface $escapedStringParser
    ) {
        $this->expressionParser = $expressionParser;
        $this->integerParser = $integerParser;
        $this->stringParser = $stringParser;
        $this->escapedStringParser = $escapedStringParser;
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
        $length = intval($source->match('/\Ga:([0-9]+):{/'), 10);

        $result = array();
        for ($i = 0; $i < $length; ++$i) {
            list($key, $source) = $this->parseKey($source);
            list($value, $source) = $this->expressionParser->parse($source);
            $result[$key] = $value;
        }

        $source->consume('}', self::CLOSE_BRACE_LENGTH);
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
            case 'S':
                return $this->escapedStringParser->parse($source);
            default:
                return $source->triggerError();
        }
    }
}
