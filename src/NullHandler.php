<?php
/**
 * parser for PHP null value
 */
namespace xKerman\Restricted;

/**
 * Parser to parse PHP serialized null value
 */
class NullHandler implements HandlerInterface
{
    /**
     * parse given `$source` as PHP serialized null value
     *
     * @param Source $source parser input
     * @param mixed  $args   submatched
     * @return array parse result
     * @throws UnserializeFailedException
     */
    public function handle(Source $source, $args)
    {
        return array($args, $source);
    }
}
