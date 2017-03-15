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
        $matched = $source->match('/\Gs:[+]?[0-9]+:"/');
        $length = intval(substr($matched, 2, -2), 10);
        $result = $source->read($length);
        $source->consume('";');

        return array($result, $source);
    }
}
