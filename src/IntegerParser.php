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
        $matched = $source->match('/\Gi:[+-]?[0-9]+;/');
        return array(intval(substr($matched, 2, -1), 10), $source);
    }
}
