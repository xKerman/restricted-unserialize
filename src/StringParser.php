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
    /** @var integer */
    const CLOSE_STRING_LENGTH = 2;

    /**
     * parse give `$source` as PHP serialized string
     *
     * @param Source $source parser input
     * @return array parser result
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $length = intval($source->match('/\Gs:([+]?[0-9]+):"/'), 10);
        $result = $source->read($length);
        $source->consume('";', self::CLOSE_STRING_LENGTH);

        return array($result, $source);
    }
}
