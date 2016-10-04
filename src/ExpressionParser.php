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
        return [$result, $source];
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
        switch ($source->peek()) {
            case 'N':
                return new NullParser();
            case 'b':
                return new BooleanParser();
            case 'i':
                return new IntegerParser();
            case 'd':
                return new FloatParser();
            case 's':
                return new StringParser();
            case 'a':
                return new ArrayParser();
            default:
                return $source->triggerError();
        }
    }
}
