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
        $result = intval($source->match('/\Gi:([+-]?[0-9]+);/'), 10);
        return array($result, $source);
    }
}
