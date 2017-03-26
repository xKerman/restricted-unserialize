<?php
/**
 * parser for serialized string
 */
namespace xKerman\Restricted;

/**
 * Parser class for parse serialized PHP stirng
 */
class StringHandler implements HandlerInterface
{
    /** @var integer */
    const CLOSE_STRING_LENGTH = 2;

    /**
     * parse give `$source` as PHP serialized string
     *
     * @param Source $source parser input
     * @param string $args   submatched
     * @return array parser result
     * @throws UnserializeFailedException
     */
    public function handle(Source $source, $args)
    {
        $length = intval($args, 10);
        $result = $source->read($length);
        $source->consume('";', self::CLOSE_STRING_LENGTH);

        return array($result, $source);
    }
}
