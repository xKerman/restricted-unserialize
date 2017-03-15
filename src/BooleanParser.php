<?php
/**
 * parser for PHP serialized boolean
 */
namespace xKerman\Restricted;

/**
 * Parser for PHP serialized boolean
 */
class BooleanParser implements ParserInterface
{
    /**
     * parse given `$source` as PHP serialized boolean
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $matched = $source->match('/\Gb:[01];/');
        if ($matched === '') {
            return $source->triggerError();
        }
        return array(boolval(substr($matched, 2, 1)), $source);
    }
}
