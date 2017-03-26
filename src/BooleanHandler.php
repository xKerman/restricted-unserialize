<?php
/**
 * parser for PHP serialized boolean
 */
namespace xKerman\Restricted;

/**
 * Parser for PHP serialized boolean
 */
class BooleanHandler implements HandlerInterface
{
    /**
     * parse given `$source` as PHP serialized boolean
     *
     * @param Source $source parser input
     * @param string $args   submatched
     * @return array
     * @throws UnserializeFailedException
     */
    public function handle(Source $source, $args)
    {
        return array((boolean)$args, $source);
    }
}
