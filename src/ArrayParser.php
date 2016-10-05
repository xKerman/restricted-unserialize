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
    /**
     * parse given `$source` as PHP serialized array
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $source->consume('a');
        $source->consume(':');

        list($length, $source) = $this->parseLength($source);
        $result = [];

        $source->consume(':');
        $source->consume('{');

        for ($i = 0; $i < $length; ++$i) {
            list($key, $source) = $this->parseKey($source);
            list($value, $source) = $this->parseValue($source);
            $result[$key] = $value;
        }

        $source->consume('}');
        return [$result, $source];
    }

    /**
     * parse given `$source` as array length
     *
     * @param Source $source input
     * @return array
     * @throws UnserializeFailedException
     */
    private function parseLength($source)
    {
        $parser = new NumberLiteralParser();
        list($length, $source) = $parser->parse($source);
        if ($length < 0) {
            return $source->triggerError();
        }
        return [$length, $source];
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
        $parser = new ExpressionParser();
        return $parser->parse($source);
    }
}
