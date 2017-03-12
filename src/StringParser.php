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
        $source->consume('s:');

        $parser = new LengthParser();
        list($length, $source) = $parser->parse($source);

        $source->consume(':"');
        $result = $source->read($length);
        $source->consume('";');

        return array($result, $source);
    }
}
