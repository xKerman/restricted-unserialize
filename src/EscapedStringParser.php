<?php
/**
 * parser for escaped string
 */
namespace xKerman\Restricted;

/**
 * Parser for escaped string
 */
class EscapedStringParser implements ParserInterface
{
    const CLOSE_STRING_LENGTH = 2;

    /**
     * parse given `$source` as escaped string
     *
     * @param Source $source parser input
     * @return array()
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $length = intval($source->match('/\GS:([+]?[0-9]+):"/'), 10);
        $result = array();
        for ($i = 0; $i < $length; ++$i) {
            $char = $source->read(1);
            if ($char !== '\\') {
                $result[] = $char;
                continue;
            }
            $result[] = chr(base_convert(($source->match('/\G[0-9a-fA-F]{2}/')), 16, 10));
        }
        $source->consume('";', self::CLOSE_STRING_LENGTH);
        return array(implode('', $result), $source);
    }
}
