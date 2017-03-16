<?php
/**
 * parser for serialized expression
 */
namespace xKerman\Restricted;

/**
 * Parser for serialized PHP values
 */
class ExpressionParser implements ParserInterface
{
    /** @var array $parsers parser list to use */
    private $parsers;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->parsers = array(
            'N' => new NullParser(),
            'b' => new TypeConvertParser(
                new RegexpSubstringParser('/\Gb:[01];/', 2, 1),
                new BooleanConverter()
            ),
            'i' => new IntegerParser(),
            'd' => new FloatParser(),
            's' => new StringParser(),
            'a' => new ArrayParser($this),
        );
    }

    /**
     * parse given `$source` as PHP serialized value
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $parser = $this->createParser($source);
        list($result, $source) = $parser->parse($source);
        return array($result, $source);
    }

    /**
     * create parser for given input
     *
     * @param Source $source input
     * @return ParserInterface
     * @throws UnserializeFailedException
     */
    private function createParser($source)
    {
        $char = $source->peek();
        if (isset($this->parsers[$char])) {
            return $this->parsers[$char];
        }
        return $source->triggerError();
    }
}
