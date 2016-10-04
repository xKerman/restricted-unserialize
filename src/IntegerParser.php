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
    /**
     * parse given `$source` as PHP serialized integer
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $source->consume('i');
        $source->consume(':');
        $parser = new NumberLiteralParser();
        list($result, $source) = $parser->parse($source);
        $source->consume(';');

        return [$result, $source];
    }
}
