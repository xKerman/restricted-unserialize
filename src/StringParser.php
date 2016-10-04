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
    /**
     * parse give `$source` as PHP serialized string
     *
     * @param Source $source parser input
     * @return array parser result
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $source->consume('s');
        $source->consume(':');
        $number = new NumberLiteralParser();
        list($length, $source) = $number->parse($source);
        if ($length < 0) {
            return $source->triggerError();
        }

        $source->consume(':');
        $source->consume('"');
        $result = '';
        for ($i = 0; $i < $length; ++$i) {
            $result .= $source->peek();
            $source->next();
        }
        $source->consume('"');
        $source->consume(';');

        return [$result, $source];
    }
}
