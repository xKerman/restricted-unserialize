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

    /** @var ParserInterface $parser internal parser */
    private $parser;

    /**
     * constructor
     *
     * @param ParserInterface $parser parser for unserialize expression
     */
    public function __construct(ParserInterface $parser)
    {
        $this->expressionParser = $parser;
        $this->parser = new TypeConvertParser(
            new RegexpSubstringParser('/\Ga:[+]?[0-9]+:{/', 2, -2),
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
            list($value, $source) = $this->parseValue($source);
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
                $parser = new IntegerParser();
                break;
            case 's':
                $parser = new StringParser();
                break;
            default:
                return $source->triggerError();
        }
        return $parser->parse($source);
    }

    /**
     * parse given `$source` as array value
     *
     * @param Source $source input
     * @return array
     * @throws UnserializeFailedException
     */
    private function parseValue($source)
    {
        return $this->expressionParser->parse($source);
    }
}
