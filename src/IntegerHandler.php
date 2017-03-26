<?php
/**
 * parser for PHP serialized integer
 */
namespace xKerman\Restricted;

/**
 * Parser for PHP serialized integer
 */
class IntegerHandler implements HandlerInterface
{
    /**
     * parse given `$source` as PHP serialized integer
     *
     * @param Source $source parser input
     * @param string $args   submatched
     * @return array
     * @throws UnserializeFailedException
     */
    public function handle(Source $source, $args)
    {
        return array(intval($args, 10), $source);
    }
}
