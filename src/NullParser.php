<?php
/**
 * parser for PHP null value
 */
namespace xKerman\Restricted;

/**
 * Parser to parse PHP serialized null value
 */
class NullParser implements ParserInterface
{
    /** @var integer */
    const NULL_TOKEN_LENGTH = 2;

    /**
     * parse given `$source` as PHP serialized null value
     *
     * @param Source $source parser input
     * @return array parse result
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $source->consume('N;', self::NULL_TOKEN_LENGTH);
        return array(null, $source);
    }
}
